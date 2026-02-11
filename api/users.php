<?php
// api/users.php
session_start();
header('Content-Type: application/json');

// Desactivar visualización de errores para no romper el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    $db_path = __DIR__ . '/../config/db.php';
    if (!file_exists($db_path)) {
        throw new Exception("Archivo de configuración no encontrado en " . $db_path);
    }
    require_once $db_path;

    if (!$conn) {
        throw new Exception("Conexión a la base de datos no disponible.");
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Obtener lista de usuarios (para el select de cambiar contraseña)
        // Se permite sin sesión para que funcione desde la pantalla de login
        $stmt = $conn->query("SELECT username, name, lastname, role FROM users WHERE username != 'marlon'");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'users' => $users]);
        exit;
    }

    // Para otras peticiones (POST) sí requerimos autenticación
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'No autorizado']);
        exit;
    }

    if ($method === 'POST') {
        // Cambiar contraseña
        $data = json_decode(file_get_contents("php://input"));
        
        // Validar acción
        if (!isset($data->action) || $data->action !== 'change_password') {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            exit;
        }

        // Solo admin puede cambiar contraseñas de otros
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Acceso denegado']);
            exit;
        }

        // Validar credenciales de admin (seguridad extra)
        $adminUsername = $data->adminUsername ?? '';
        $adminPassword = $data->adminPassword ?? '';
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND password = :password AND role = 'admin'");
        $stmt->execute([':username' => $adminUsername, ':password' => $adminPassword]);
        
        if (!$stmt->fetch()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Credenciales de administrador incorrectas']);
            exit;
        }

        // Actualizar datos del usuario objetivo
        $targetUser = $data->userToChange ?? '';
        $newPassword = $data->newPassword ?? '';
        $newEmail = $data->newEmail ?? '';

        if (empty($targetUser) || empty($newPassword)) {
            echo json_encode(['success' => false, 'error' => 'Datos de usuario o contraseña vacíos']);
            exit;
        }

        if (!empty($newEmail)) {
            $updateStmt = $conn->prepare("UPDATE users SET password = :password, email = :email WHERE username = :username");
            $updateStmt->execute([':password' => $newPassword, ':email' => $newEmail, ':username' => $targetUser]);
        } else {
            $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE username = :username");
            $updateStmt->execute([':password' => $newPassword, ':username' => $targetUser]);
        }

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    }

} catch (Exception $e) {
    // Si algo falla, devolver error en JSON
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
