<?php
// api/settings.php
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
    try {
        $stmt = $conn->query("SELECT setting_key, setting_value FROM app_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        echo json_encode($settings);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    // Solo admins pueden modificar
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos']);
        exit;
    }

    try {
        $conn->beginTransaction();
        $stmt = $conn->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE setting_value = :value");
        
        foreach ($data as $key => $value) {
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
