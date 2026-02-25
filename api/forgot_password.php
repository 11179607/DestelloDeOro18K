<?php
// api/forgot_password.php
header('Content-Type: application/json');
require_once '../config/db.php';

// Rutas de PHPMailer
$phpmailer_path   = '../libs/PHPMailer/PHPMailer.php';
$smtp_path        = '../libs/PHPMailer/SMTP.php';
$exception_path   = '../libs/PHPMailer/Exception.php';

if (!file_exists($phpmailer_path) || !file_exists($smtp_path) || !file_exists($exception_path)) {
    echo json_encode(['success' => false, 'message' => 'Error: Faltan archivos de PHPMailer en libs/PHPMailer.']);
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

$role  = trim($data->role ?? '');
$email = trim($data->email ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido.']);
    exit;
}

try {
    // 1. Verificar usuario por rol y correo
    $stmt = $conn->prepare("SELECT id, username, name FROM users WHERE role = :role AND email = :email LIMIT 1");
    $stmt->execute([':role' => $role, ':email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'El correo no coincide con el rol seleccionado.']);
        exit;
    }

    // 2. Generar token y expiración
    $token  = bin2hex(random_bytes(16));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // 3. Guardar token
    $conn->prepare("UPDATE users SET reset_token = :t, reset_token_expiry = :e WHERE id = :id")
         ->execute([':t' => $token, ':e' => $expiry, ':id' => $user['id']]);

    // 4. Preparar correo
    $mail = new PHPMailer(true);

    try {
        // SMTP configurable por variables de entorno
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER') ?: 'marloncdela@gmail.com';
        $mail->Password   = getenv('SMTP_PASS') ?: 'gkkw jbnz jkie rpet'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = getenv('SMTP_PORT') ?: 587;
        $mail->CharSet    = 'UTF-8';

        // Emisor y receptor
        $mail->setFrom($mail->Username, 'Destello de Oro 18K');
        $mail->addReplyTo($mail->Username, 'Destello de Oro 18K');
        $mail->addAddress($email, $user['name']);

        // Construir enlace
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseUrl  = rtrim($protocol . "://" . $host . dirname($_SERVER['PHP_SELF'], 2), '/');
        $resetLink = $baseUrl . "/reset_password.php?token=" . $token;

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Restablecer Contraseña - Destello de Oro 18K';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #d4af37; border-radius: 10px;'>
                <h2 style='color: #d4af37; text-align: center;'>Destello de Oro 18K</h2>
                <p>Hola <strong>{$user['name']}</strong>,</p>
                <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para continuar:</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetLink}' style='background: #d4af37; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Restablecer Contraseña</a>
                </div>
                <p>Si no puedes hacer clic en el botón, copia y pega este enlace en tu navegador:</p>
                <p style='color: #666; font-size: 0.9rem;'>{$resetLink}</p>
                <p>Este enlace expirará en 1 hora.</p>
                <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                <p style='font-size: 0.8rem; color: #999; text-align: center;'>Este es un correo automático, por favor no respondas.</p>
            </div>
        ";

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Se ha enviado un correo con las instrucciones para restablecer tu contraseña.']);
    } catch (Exception $e) {
        // Fallback a mail() si SMTP falla
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Destello de Oro 18K <{$mail->Username}>\r\n";
        $sentFallback = @mail($email, 'Restablecer Contraseña - Destello de Oro 18K', $mail->Body, $headers);

        if ($sentFallback) {
            echo json_encode(['success' => true, 'message' => 'Se ha enviado un correo con las instrucciones para restablecer tu contraseña.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'El correo no pudo ser enviado. Error SMTP: ' . $mail->ErrorInfo]);
        }
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
