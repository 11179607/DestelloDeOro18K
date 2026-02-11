<?php
// api/users.php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener lista de usuarios (para el select de cambiar contraseña)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    try {
        $stmt = $conn->query("SELECT username, name, lastname, role FROM users WHERE username != 'marlon'");
        $users = $stmt->fetchAll();
        echo json_encode(['success' => true, 'users' => $users]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Cambiar contraseña
    $data = json_decode(file_get_contents("php://input"));
    
    // Validar acción
    if (!isset($data->action) || $data->action !== 'change_password') {
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
        exit;
    }

    // Solo admin puede cambiar contraseñas de otros
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    // Validar credenciales de admin (seguridad extra)
    $adminUsername = $data->adminUsername;
    $adminPassword = $data->adminPassword;
    
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND password = :password AND role = 'admin'");
        $stmt->execute([':username' => $adminUsername, ':password' => $adminPassword]);
        
        if (!$stmt->fetch()) {
            http_response_code(401);
            echo json_encode(['error' => 'Credenciales de administrador incorrectas']);
            exit;
        }

        // Actualizar datos del usuario objetivo
        $targetUser = $data->userToChange;
        $newPassword = $data->newPassword;
        $newEmail = $data->newEmail ?? '';

        if (!empty($newEmail)) {
            $updateStmt = $conn->prepare("UPDATE users SET password = :password, email = :email WHERE username = :username");
            $updateStmt->execute([':password' => $newPassword, ':email' => $newEmail, ':username' => $targetUser]);
        } else {
            $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE username = :username");
            $updateStmt->execute([':password' => $newPassword, ':username' => $targetUser]);
        }

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
