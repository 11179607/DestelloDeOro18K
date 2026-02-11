<?php
// api/migrate_forgot_password.php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../config/db.php';

echo "<h2>Migración de Base de Datos - Sistema de Recuperación</h2>";

$queries = [
    "ALTER TABLE users ADD COLUMN email VARCHAR(150) AFTER role",
    "ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL",
    "ALTER TABLE users ADD COLUMN reset_token_expiry DATETIME DEFAULT NULL"
];

foreach ($queries as $query) {
    try {
        $conn->exec($query);
        echo "<p style='color: green;'>✅ Ejecutado exitosamente: <code>$query</code></p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<p style='color: blue;'>ℹ️ La columna ya existe: <code>$query</code></p>";
        } else {
            echo "<p style='color: red;'>❌ Error en query: <code>$query</code>. Error: " . $e->getMessage() . "</p>";
        }
    }
}

try {
    $email = 'marloncdela@gmail.com';
    $stmt = $conn->prepare("UPDATE users SET email = :email WHERE username IN ('admin', 'trabajador')");
    $stmt->execute([':email' => $email]);
    echo "<p style='color: green;'>✅ Correo <b>$email</b> asignado a los usuarios 'admin' y 'trabajador'.</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error asignando correos: " . $e->getMessage() . "</p>";
}

echo "<h3>Migración terminada.</h3>";
echo "<p><a href='../index.php'>Volver al inicio</a></p>";
?>
