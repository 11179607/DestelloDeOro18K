<?php
// api/reset_db.php
header('Content-Type: application/json');
require_once '../config/db.php';

try {
    // 1. Asegurar que los usuarios existan (si no existen, los crea)
    $conn->exec("INSERT IGNORE INTO users (username, password, role, name, lastname, phone) VALUES 
        ('admin', 'admin123', 'admin', 'Administrador', 'Principal', '3001234567'),
        ('trabajador', 'trabajador123', 'worker', 'Vendedor', 'Principal', '3009876543')");

    // 2. Resetear contraseñas
    $stmt1 = $conn->prepare("UPDATE users SET password = 'admin123' WHERE username = 'admin'");
    $stmt1->execute();

    $stmt2 = $conn->prepare("UPDATE users SET password = 'trabajador123' WHERE username = 'trabajador'");
    $stmt2->execute();

    // 3. Limpiar variables de sesión de PHP
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();

    echo json_encode([
        'success' => true, 
        'message' => 'Contraseña de admin reseteada correctamente a admin123'
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al resetear base de datos: ' . $e->getMessage()]);
}
?>
