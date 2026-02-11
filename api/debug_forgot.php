<?php
// api/debug_forgot.php
header('Content-Type: text/html; charset=utf-8');
require_once '../config/db.php';

echo "<h2>Informe de Diagnóstico - Recuperación de Contraseña</h2>";

// 1. Verificar PHPMailer
echo "<h3>1. Verificando Archivos de PHPMailer:</h3>";
$files = [
    '../libs/PHPMailer/PHPMailer.php',
    '../libs/PHPMailer/SMTP.php',
    '../libs/PHPMailer/Exception.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ Encontrado: $file</p>";
    } else {
        echo "<p style='color: red;'>❌ NO ENCONTRADO: $file (Debes subir este archivo a tu servidor)</p>";
    }
}

// 2. Verificar Base de Datos
echo "<h3>2. Verificando Estructura de Base de Datos:</h3>";
try {
    $stmt = $conn->query("DESCRIBE users");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required = ['email', 'reset_token', 'reset_token_expiry'];
    foreach ($required as $req) {
        if (in_array($req, $cols)) {
            echo "<p style='color: green;'>✅ Columna '$req' existe.</p>";
        } else {
            echo "<p style='color: red;'>❌ Falta columna '$req' (Debes crearla en phpMyAdmin)</p>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al leer tabla 'users': " . $e->getMessage() . "</p>";
}

// 3. Verificar Configuración SMTP
echo "<h3>3. Verificando script de envío:</h3>";
if (file_exists('forgot_password.php')) {
    echo "<p style='color: green;'>✅ El archivo api/forgot_password.php existe.</p>";
} else {
    echo "<p style='color: red;'>❌ El archivo api/forgot_password.php NO EXISTE en esta carpeta.</p>";
}

echo "<hr><p>Si ves algún aspa roja (❌), esa es la razón del error. Corrige ese punto y el sistema funcionará.</p>";
?>
