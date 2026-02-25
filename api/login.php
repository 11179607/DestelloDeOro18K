<?php
// api/login.php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$username = $data->username;
$password = $data->password;

try {
    // Buscar usuario
    $stmt = $conn->prepare("SELECT id, username, password, role, name, lastname FROM users WHERE username = :username LIMIT 1");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $user = $stmt->fetch();
    
    // Verificar contraseña (Texto plano por ahora según setup.sql)
    // En el futuro: password_verify($password, $user['password'])
    if ($user && $password === $user['password']) {
        
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
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
    }

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>
