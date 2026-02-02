<?php
// api/restocks.php
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
        $month = $_GET['month'] ?? null;
        $year = $_GET['year'] ?? null;
        
        $sql = "SELECT * FROM restocks";
        $params = [];
        
        if ($month !== null && $year !== null) {
            $month = intval($month) + 1;
            $sql .= " WHERE MONTH(restock_date) = :month AND YEAR(restock_date) = :year";
            $params[':month'] = $month;
            $params[':year'] = $year;
        }
        
        $sql .= " ORDER BY restock_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll();
        echo json_encode($result);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Surtir inventario (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));
    
    if (!$data || !isset($data->id) || !isset($data->quantity)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    try {
        $conn->beginTransaction();
        
        // 1. Aumentar stock
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity + :qty WHERE reference = :ref");
        $stmt->execute([':qty' => $data->quantity, ':ref' => $data->id]);
        
        // 2. Registrar historial
        // Primero obtener nombre del producto
        $pStmt = $conn->prepare("SELECT name FROM products WHERE reference = :ref");
        $pStmt->execute([':ref' => $data->id]);
        $product = $pStmt->fetch();
        $pName = $product ? $product['name'] : 'Producto desconocido';
        
        $histStmt = $conn->prepare("INSERT INTO restocks (product_ref, product_name, quantity, user_id, username) VALUES (:ref, :name, :qty, :uid, :uname)");
        $histStmt->execute([
            ':ref' => $data->id,
            ':name' => $pName,
            ':qty' => $data->quantity,
            ':uid' => $_SESSION['user_id'],
            ':uname' => $_SESSION['username']
        ]);
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Inventario surtido correctamente']);

    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
