<?php
// api/verify_admin_password.php
require_once '../config/db.php';
session_start();

header('Content-Type: application/json');

// Solo post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Verificar que esté logueado y sea admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Acceso denegado. Se requiere cuenta de administrador.']);
    exit;
}

// Recibir el JSON
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || empty($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La contraseña es requerida.']);
    exit;
}

$password = $data['password'];
$userId = $_SESSION['user_id'];

try {
    // Buscar usuario admin en base de datos
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id AND role = 'admin'");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // En tu login.php dice "// En el futuro: password_verify($password, $user['password'])"
    // Y hace una comparación en texto plano "if ($user && $password === $user['password'])"
    // Así que lo mantendremos igual aquí para que coincida.
    
    if (!$user || $password !== $user['password']) {
        // Falló en texto plano. (Si ya estuvieran encriptadas, usarías password_verify)
        if (!password_verify($password, $user['password']) && $password !== $user['password']) {
            echo json_encode(['success' => false, 'error' => 'Contraseña de administrador incorrecta.']);
            exit;
        }
    }

    // Contraseña correcta
    echo json_encode(['success' => true, 'message' => 'Contraseña verificada correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error de servidor: ' . $e->getMessage()]);
}
?>
