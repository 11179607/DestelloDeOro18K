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
} elseif ($method === 'DELETE') {
    // Eliminar Surtido (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'ID no proporcionado']);
        exit;
    }

    try {
        $conn->beginTransaction();

        // 1. Obtener datos del surtido para descontar stock
        $stmt = $conn->prepare("SELECT product_ref, quantity FROM restocks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $restock = $stmt->fetch();

        if ($restock) {
            $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity - :qty WHERE reference = :ref");
            $stockStmt->execute([
                ':qty' => $restock['quantity'],
                ':ref' => $restock['product_ref']
            ]);
        }

        // 2. Eliminar el registro de surtido
        $deleteStmt = $conn->prepare("DELETE FROM restocks WHERE id = :id");
        $deleteStmt->execute([':id' => $id]);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Surtido eliminado y stock ajustado']);
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'PUT') {
    // Editar Surtido (Solo admin)
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

        // 1. Obtener datos actuales
        $stmt = $conn->prepare("SELECT product_ref, quantity FROM restocks WHERE id = :id");
        $stmt->execute([':id' => $data->id]);
        $oldRestock = $stmt->fetch();

        if ($oldRestock) {
            $diff = $data->quantity - $oldRestock['quantity'];
            
            // 2. Ajustar stock del producto
            $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity + :qty WHERE reference = :ref");
            $stockStmt->execute([
                ':qty' => $diff,
                ':ref' => $oldRestock['product_ref']
            ]);

            // 3. Actualizar el registro
            $updateStmt = $conn->prepare("UPDATE restocks SET quantity = :qty WHERE id = :id");
            $updateStmt->execute([':qty' => $data->quantity, ':id' => $data->id]);
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Surtido actualizado y stock ajustado']);
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
