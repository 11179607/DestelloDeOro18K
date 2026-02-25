<?php
// api/login.php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si está bloqueado
    if ($user && $user['locked_until'] !== null && strtotime($user['locked_until']) > time()) {
        $restante = ceil((strtotime($user['locked_until']) - time()) / 60);
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => "Cuenta bloqueada temporalmente por seguridad. Intenta nuevamente en $restante minutos."]);
        exit;
    }

    // Verificar contraseña (acepta hash o texto plano legacy)
    $isValid = false;
    if ($user) {
        $stored = $user['password'];
        if (password_verify($password, $stored) || $password === $stored) {
            $isValid = true;
        }
    }

    if ($isValid) {
        // Resetear intentos y desbloqueo
        $resStmt = $conn->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL, security_token = NULL WHERE id = :id");
        $resStmt->execute([':id' => $user['id']]);

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
        exit;
    }

    // Manejo de credenciales inválidas
    if ($user) {
        $attempts = intval($user['failed_attempts'] ?? 0) + 1;

        if ($attempts >= 2) {
            // Generar token de seguridad
            $token = bin2hex(random_bytes(16));

            $upd = $conn->prepare("UPDATE users SET failed_attempts = :att, security_token = :tok WHERE id = :id");
            $upd->execute([':att' => $attempts, ':tok' => $token, ':id' => $user['id']]);

            // Enviar correo de alerta si hay email
            if (!empty($user['email'])) {
                $phpmailer_path = '../libs/PHPMailer/PHPMailer.php';
                $smtp_path = '../libs/PHPMailer/SMTP.php';
                $exception_path = '../libs/PHPMailer/Exception.php';
                if (file_exists($phpmailer_path) && file_exists($smtp_path) && file_exists($exception_path)) {
                    require_once $phpmailer_path;
                    require_once $smtp_path;
                    require_once $exception_path;

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'marloncdela@gmail.com';
                        $mail->Password   = 'gkkwjbnzjkierpet'; // contraseña de aplicación
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;
                        $mail->CharSet    = 'UTF-8';

                        $mail->setFrom('no-reply@destellodeoro.com', 'Destello de Oro 18K Seguridad');
                        $mail->addAddress($user['email'], $user['name'] ?? $user['username']);

                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
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
                        // Silenciar errores de envío para no filtrar información
                    }
                }
            }

            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Múltiples intentos fallidos. Por seguridad, hemos enviado un mensaje a tu correo.']);
        } else {
            $upd = $conn->prepare("UPDATE users SET failed_attempts = :att WHERE id = :id");
            $upd->execute([':att' => $attempts, ':id' => $user['id']]);

            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Credenciales inválidas. ' . (2 - $attempts) . ' intento(s) restante(s).']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
