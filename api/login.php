<?php
// api/login.php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$username = $data->username;
$password = $data->password;

try {
    // Buscar usuario
    $stmt = $conn->prepare("SELECT id, username, password, role, name, lastname, email, failed_attempts, locked_until FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch();

    if ($user) {
        // Verificar si está bloqueado
        if ($user['locked_until'] !== null && strtotime($user['locked_until']) > time()) {
            http_response_code(403);
            $restante = ceil((strtotime($user['locked_until']) - time()) / 60);
            echo json_encode(['success' => false, 'message' => "Cuenta bloqueada temporalmente por seguridad. Intenta nuevamente en $restante minutos."]);
            exit;
        }
    }
    
    // Verificar contraseña (Texto plano por ahora según setup.sql)
    // En el futuro: password_verify($password, $user['password'])
    if ($user && $password === $user['password']) {
        // Resetear intentos
        $resStmt = $conn->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL, security_token = NULL WHERE id = :id");
        $resStmt->execute(['id' => $user['id']]);

        // Guardar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'] . ' ' . $user['lastname'];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login exitoso',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'name' => $user['name'] . ' ' . $user['lastname']
            ]
        ]);
    } else {
        if ($user) {
            // Manejar intentos fallidos
            $attempts = ($user['failed_attempts'] ?? 0) + 1;
            
            if ($attempts >= 2) {
                // Generar token para la confirmación
                $token = bin2hex(random_bytes(16));
                
                // Actualizar token sin bloquear todavía (se bloquea si dice que NO fue él)
                $upd = $conn->prepare("UPDATE users SET failed_attempts = :att, security_token = :tok WHERE id = :id");
                $upd->execute(['att' => $attempts, 'tok' => $token, 'id' => $user['id']]);

                // ENVIAR EL CORREO DE ALERTA
                require_once '../libs/PHPMailer/PHPMailer.php';
                require_once '../libs/PHPMailer/SMTP.php';
                require_once '../libs/PHPMailer/Exception.php';

                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'marloncdela@gmail.com';
                    $mail->Password   = 'rbadivlrudndxmfa';
                    $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    $mail->CharSet    = 'UTF-8';

                    $mail->setFrom('marloncdela@gmail.com', 'Destello de Oro 18K Seg');
                    $mail->addAddress($user['email'], $user['name']);

                    // URLs de confirmación
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'];
                    $base_url = $protocol . "://" . $host . dirname($_SERVER['PHP_SELF'], 2);
                    
                    $link_yes = $base_url . "/verify_login_attempt.php?token=" . $token . "&action=yes";
                    $link_no  = $base_url . "/verify_login_attempt.php?token=" . $token . "&action=no";

                    $mail->isHTML(true);
                    $mail->Subject = 'Alerta de Seguridad - Destello de Oro 18K';
                    $mail->Body    = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #d4af37; border-radius: 10px;'>
                            <h2 style='color: #d4af37; text-align: center;'>Alerta de Seguridad</h2>
                            <p>Hola <strong>{$user['name']}</strong>,</p>
                            <p>Hemos detectado múltiples intentos fallidos de inicio de sesión en tu cuenta.</p>
                            <p><strong>¿Fuiste tú intentando acceder?</strong></p>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='{$link_yes}' style='background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-right: 15px;'>Sí, fui yo</a>
                                <a href='{$link_no}' style='background: #dc3545; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>No, no fui yo</a>
                            </div>
                            <p>Si indicas que no fuiste tú, por precaución bloquearemos el acceso temporalmente.</p>
                        </div>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    // Ignorar error de correo para no delatar info
                }
                
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Múltiples intentos fallidos. Por seguridad, hemos enviado un mensaje a tu correo.']);
            } else {
                $upd = $conn->prepare("UPDATE users SET failed_attempts = :att WHERE id = :id");
                $upd->execute(['att' => $attempts, 'id' => $user['id']]);
                
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Credenciales inválidas. ' . (2 - $attempts) . ' intento(s) restante(s).']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        }
    }

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
