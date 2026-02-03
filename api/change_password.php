<?php
// api/change_password.php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    // Validar datos requeridos
    if (!isset($data->admin_username) || !isset($data->admin_password) || 
        !isset($data->user_to_change) || !isset($data->new_password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }

    $adminUsername = $data->admin_username;
    $adminPassword = $data->admin_password;
    $userToChange = $data->user_to_change;
    $newPassword = $data->new_password;
    
    try {
        // Verificar credenciales de administrador
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND password = :password AND role = 'admin'");
        $stmt->execute([':username' => $adminUsername, ':password' => $adminPassword]);
        
        if (!$stmt->fetch()) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Credenciales de administrador incorrectas']);
            exit;
        }

        // Actualizar contraseña del usuario objetivo
        $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE username = :username");
        $updateStmt->execute([':password' => $newPassword, ':username' => $userToChange]);

        if ($updateStmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado o contraseña no cambiada']);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
