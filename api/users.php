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
        throw new Exception("Archivo de configuración no encontrado.");
    }
    require_once $db_path;

    if (!$conn) {
        throw new Exception("Conexión a la base de datos no disponible.");
    }

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // Obtener lista de usuarios (para el select de cambiar contraseña)
        $stmt = $conn->query("SELECT username, name, lastname, role FROM users WHERE username != 'marlon'");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'users' => $users]);
        exit;
    }

    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!$data) {
            throw new Exception("Datos no recibidos correctamente.");
        }

        // Caso especial: Cambio de contraseña desde el login (autorizado con credenciales admin en el cuerpo)
        if (isset($data->action) && $data->action === 'change_password') {
            $adminUsername = $data->adminUsername ?? '';
            $adminPassword = $data->adminPassword ?? '';
            
            // Validar credenciales de admin dadas en el body
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username AND password = :password AND role = 'admin'");
            $stmt->execute([':username' => $adminUsername, ':password' => $adminPassword]);
            
            if (!$stmt->fetch()) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Credenciales de administrador incorrectas']);
                exit;
            }

            // Si las credenciales son correctas, procedemos al cambio
            $targetUser = $data->userToChange ?? '';
            $newPassword = $data->newPassword ?? '';
            $newEmail = $data->newEmail ?? '';

            if (empty($targetUser) || empty($newPassword)) {
                throw new Exception("Datos de usuario o contraseña incompletos.");
            }

            // Validar seguridad de la contraseña: una mayúscula, un número y un carácter especial
            if (!preg_match('/[A-Z]/', $newPassword) || 
                !preg_match('/[0-9]/', $newPassword) || 
                !preg_match('/[^A-Za-z0-9]/', $newPassword)) {
                throw new Exception("La contraseña debe tener al menos una letra mayúscula, un número y un carácter especial.");
            }

            if (!empty($newEmail)) {
                $updateStmt = $conn->prepare("UPDATE users SET password = :password, email = :email WHERE username = :username");
                $updateStmt->execute([':password' => $newPassword, ':email' => $newEmail, ':username' => $targetUser]);
            } else {
                $updateStmt = $conn->prepare("UPDATE users SET password = :password WHERE username = :username");
                $updateStmt->execute([':password' => $newPassword, ':username' => $targetUser]);
            }

            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente']);
            exit;
        }

        // Para cualquier otro POST futuro, sí requerimos sesión
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }
        
        throw new Exception("Acción no reconocida.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
