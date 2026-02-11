<?php
// api/migrate_forgot_password.php
require_once __DIR__ . '/../config/db.php';

try {
    // 1. Agregar columna email, reset_token y reset_token_expiry a la tabla users
    $queries = [
        "ALTER TABLE users ADD COLUMN email VARCHAR(150) AFTER role",
        "ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL",
        "ALTER TABLE users ADD COLUMN reset_token_expiry DATETIME DEFAULT NULL"
    ];

    foreach ($queries as $query) {
        try {
            $conn->exec($query);
            echo "Ejecutado: $query <br>";
        } catch (PDOException $e) {
            echo "Error o ya existe: " . $e->getMessage() . "<br>";
        }
    }

    // 2. Asignar correos por defecto a los usuarios existentes si están vacíos
    $conn->exec("UPDATE users SET email = 'admin@destellodeoro.com' WHERE username = 'admin' AND (email IS NULL OR email = '')");
    $conn->exec("UPDATE users SET email = 'trabajador@destellodeoro.com' WHERE username = 'trabajador' AND (email IS NULL OR email = '')");

    echo "Migración completada con éxito.";
} catch (PDOException $e) {
    echo "Error general: " . $e->getMessage();
}
?>
