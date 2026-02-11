<?php
// api/forgot_password.php
header('Content-Type: application/json');
require_once '../config/db.php';

// Cargar PHPMailer
$phpmailer_path = '../libs/PHPMailer/PHPMailer.php';
$smtp_path = '../libs/PHPMailer/SMTP.php';
$exception_path = '../libs/PHPMailer/Exception.php';

if (!file_exists($phpmailer_path) || !file_exists($smtp_path) || !file_exists($exception_path)) {
    echo json_encode(['success' => false, 'message' => 'Error: No se han subido los archivos de PHPMailer a la carpeta libs/']);
    exit;
}

require_once $phpmailer_path;
require_once $smtp_path;
require_once $exception_path;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->role) || !isset($data->email)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$role = $data->role;
$email = $data->email;

try {
    // 1. Verificar si el usuario existe con ese rol y correo
    $stmt = $conn->prepare("SELECT id, username, name FROM users WHERE role = :role AND email = :email LIMIT 1");
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $user = $stmt->fetch();
    
    if (!$user) {
        // Por seguridad, aveces es mejor decir que se envió el correo igual si existe, 
        // pero el usuario pidió "confirmar el correo registrado", así que daremos error si no coincide.
        echo json_encode(['success' => false, 'message' => 'El correo no coincide con el rol seleccionado.']);
        exit;
    }
    
    // 2. Generar token
    $token = bin2hex(random_bytes(16));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // 3. Guardar token en la base de datos
    $updateStmt = $conn->prepare("UPDATE users SET reset_token = :token, reset_token_expiry = :expiry WHERE id = :id");
    $updateStmt->bindParam(':token', $token);
    $updateStmt->bindParam(':expiry', $expiry);
    $updateStmt->bindParam(':id', $user['id']);
    $updateStmt->execute();
    
    // 4. Enviar correo con PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP - EL USUARIO DEBE COMPLETAR ESTO
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Servidor SMTP (ej: smtp.gmail.com)
        $mail->SMTPAuth   = true;
        $mail->Username   = 'marloncdela@gmail.com'; // Correo emisor
        $mail->Password   = 'gkkw jbnz jkie rpet'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->setCharset('UTF-8');

        // Emisor y receptor
        $mail->setFrom('no-reply@destellodeoro.com', 'Destello de Oro 18K');
        $mail->addAddress($email, $user['name']);

        // Contenido del correo
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        // Ajustar la ruta si el proyecto está en una subcarpeta
        $base_url = $protocol . "://" . $host . dirname($_SERVER['PHP_SELF'], 2);
        $reset_link = $base_url . "/reset_password.php?token=" . $token;

        $mail->isHTML(true);
        $mail->Subject = 'Restablecer Contraseña - Destello de Oro 18K';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #d4af37; border-radius: 10px;'>
                <h2 style='color: #d4af37; text-align: center;'>Destello de Oro 18K</h2>
                <p>Hola <strong>{$user['name']}</strong>,</p>
                <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para continuar:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$reset_link}' style='background: #d4af37; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Restablecer Contraseña</a>
                </div>
                <p>Si no puedes hacer clic en el botón, copia y pega el siguiente enlace en tu navegador:</p>
                <p style='color: #666; font-size: 0.9rem;'>{$reset_link}</p>
                <p>Este enlace expirará en 1 hora.</p>
                <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 0.8rem; color: #999; text-align: center;'>Este es un correo automático, por favor no respondas.</p>
            </div>
        ";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Se ha enviado un correo con las instrucciones para restablecer tu contraseña.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'El correo no pudo ser enviado. Error: ' . $mail->ErrorInfo]);
    }

} catch (PDOException $e) {
    $msg = $e->getMessage();
    if (strpos($msg, '1054') !== false) {
        try {
            $check = $conn->query("DESCRIBE users");
            $cols = $check->fetchAll(PDO::FETCH_COLUMN);
            $msg .= " | Columnas encontradas en 'users': " . implode(', ', $cols);
        } catch (Exception $e2) {
            $msg .= " | No se pudo listar columnas de 'users'.";
        }
    }
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $msg]);
}
?>
