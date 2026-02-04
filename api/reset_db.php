<?php
// api/reset_db.php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

try {
    // Si la petición es POST y el modo es full_wipe
    $data = json_decode(file_get_contents("php://input"));
    $mode = $data->mode ?? ($_GET['mode'] ?? 'passwords');

    if ($mode === 'full_wipe') {
        // 1. Verificar que sea administrador en la sesión
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acceso denegado. Solo el administrador puede resetear los registros.']);
            exit;
        }

        // 2. Verificar la contraseña del administrador enviada en el body
        if (!isset($data->password)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Se requiere la contraseña del administrador.']);
            exit;
        }

        // Buscar el usuario admin en la DB para verificar su contraseña actual
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = 'admin' LIMIT 1");
        $stmt->execute();
        $admin = $stmt->fetch();

        if (!$admin || $data->password !== $admin['password']) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Contraseña de administrador incorrecta.']);
            exit;
        }

        // MODO RESET TOTAL: Borra todos los movimientos y deja stock en 0
        
        // Desactivar chequeo de llaves foráneas
        $conn->exec("SET FOREIGN_KEY_CHECKS = 0");

        // Truncar tablas de movimientos
        $conn->exec("TRUNCATE TABLE sale_items");
        $conn->exec("TRUNCATE TABLE sales");
        $conn->exec("TRUNCATE TABLE expenses");
        $conn->exec("TRUNCATE TABLE restocks");
        $conn->exec("TRUNCATE TABLE warranties");
        
        // Si existe la tabla de logs
        $conn->exec("TRUNCATE TABLE audit_logs");

        // Resetear stock de productos a 0
        $conn->exec("UPDATE products SET quantity = 0");

        // Reactivar chequeo de llaves foráneas
        $conn->exec("SET FOREIGN_KEY_CHECKS = 1");

        echo json_encode([
            'success' => true, 
            'message' => 'SISTEMA RESTAURADO: Todos los movimientos han sido eliminados y el stock puesto en 0.'
        ]);

    } else {
        // MODO DEFAULT (GET): Reseteos básicos si se accede por URL directamente (legacy support o passwords reset)
        // Podríamos restringir esto también si se desea, pero por ahora lo dejamos similar
        
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
        session_destroy();

        echo json_encode([
            'success' => true, 
            'message' => 'Contraseñas reseteadas a valores por defecto (admin123 / trabajador123) y sesión cerrada.'
        ]);
    }

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al resetear base de datos: ' . $e->getMessage()]);
}
?>
