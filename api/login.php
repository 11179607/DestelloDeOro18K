<?php
// api/login.php
session_start();
header("Content-Type: application/json");
require_once "../config/db.php";

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$username = $data->username;
$password = $data->password;

try {
    // Buscar usuario
    $stmt = $conn->prepare("SELECT id, username, password, role, name, lastname, email, failed_attempts, locked_until FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si está bloqueado
    if ($user && $user['locked_until'] !== null && strtotime($user['locked_until']) > time()) {
        $restante = ceil((strtotime($user['locked_until']) - time()) / 60);
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => "Cuenta bloqueada temporalmente por seguridad. Intenta nuevamente en $restante minutos."]);
        exit;
    }

    // Verificar contraseña (acepta hash o texto plano legacy)
    $isValid = false;
    if ($user) {
        $stored = $user['password'];
        if (password_verify($password, $stored) || $password === $stored) {
            $isValid = true;
        }
    }

    if ($isValid) {
        // Resetear intentos y desbloqueo
        $resStmt = $conn->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL, security_token = NULL WHERE id = :id");
        $resStmt->execute([':id' => $user['id']]);

        // Guardar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'] . ' ' . $user['lastname'];

        echo json_encode([
            'success' => true,
            'message' => 'Login exitoso',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'name' => $user['name'] . ' ' . $user['lastname']
            ]
        ]);
        exit;
    }

    // Manejo de credenciales inválidas
    if ($user) {
        $attempts = intval($user['failed_attempts'] ?? 0) + 1;

        $upd = $conn->prepare("UPDATE users SET failed_attempts = :att WHERE id = :id");
        $upd->execute([':att' => $attempts, ':id' => $user['id']]);

        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
