<?php
// api/logs.php
session_start();
header('Content-Type: application/json');
require_once '../config/db.php';
require_once 'logger.php';

// Verificar autenticación y rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado. Solo administradores pueden ver el historial de auditoría.']);
    exit;
}

// Asegurar que la tabla existe antes de intentar leer
ensureLogsTableExists($conn);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        // Obtener logs ordenados por fecha descendente (más recientes primero)
        // Limitar a los últimos 500 registros para rendimiento
        $stmt = $conn->query("SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 500");
        $logs = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'logs' => $logs]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al obtener logs: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
