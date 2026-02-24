<?php
require_once 'libs/PHPMailer/PHPMailer.php';
require_once 'libs/PHPMailer/SMTP.php';
require_once 'libs/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER;
$mail->Debugoutput = 'echo';
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'marloncdela@gmail.com';
$mail->Password   = 'gkkwjbnzjkierpet';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

try {
    $mail->setFrom('marloncdela@gmail.com', 'Test');
    $mail->addAddress('marloncdela@gmail.com');
    $mail->Subject = 'Test';
    $mail->Body = 'Test';
    $mail->send();
    echo "\nOK\n";
} catch (Exception $e) {
    echo "\nError: " . $mail->ErrorInfo . "\n";
}
