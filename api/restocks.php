<?php
// api/restocks.php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
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
            if ($month === 'all') {
                $sql = "SELECT r.*, p.purchase_price FROM restocks r LEFT JOIN products p ON r.product_ref = p.reference WHERE YEAR(r.restock_date) = :year";
                $params[':year'] = $year;
            } else {
                $month = intval($month) + 1;
                // Usar alias r para restocks
                $sql = "SELECT r.*, p.purchase_price FROM restocks r LEFT JOIN products p ON r.product_ref = p.reference WHERE MONTH(r.restock_date) = :month AND YEAR(r.restock_date) = :year";
                $params[':month'] = $month;
                $params[':year'] = $year;
            }
        } else {
             $sql = "SELECT r.*, p.purchase_price FROM restocks r LEFT JOIN products p ON r.product_ref = p.reference";
        }
        
        $sql .= " ORDER BY r.restock_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $restocks = $stmt->fetchAll();
        
        // Map database fields to JS expected fields
        foreach ($restocks as &$restock) {
            $restock['date'] = $restock['restock_date'];
            $restock['productName'] = $restock['product_name'];
            $restock['productId'] = $restock['product_ref'];
            $restock['user'] = $restock['username'];
            
            // Calcular valor total basado en precio de compra actual
            $purchasePrice = (float)($restock['purchase_price'] ?? 0);
            $restock['purchasePrice'] = $purchasePrice;
            $restock['totalValue'] = $restock['quantity'] * $purchasePrice;
        }
        
        echo json_encode($restocks);
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
        
        $incomingDate = $data->date ?? null;
        $restockDate = $incomingDate ? ((strlen($incomingDate) === 10) ? ($incomingDate . ' ' . date('H:i:s')) : $incomingDate) : date('Y-m-d H:i:s');

        $histStmt = $conn->prepare("INSERT INTO restocks (product_ref, product_name, quantity, restock_date, user_id, username) VALUES (:ref, :name, :qty, :date, :uid, :uname)");
        $histStmt->execute([
            ':ref' => $data->id,
            ':name' => $pName,
            ':qty' => $data->quantity,
            ':date' => $restockDate,
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
            $incomingDate = $data->date ?? null;
            $restockDate = $incomingDate ? ((strlen($incomingDate) === 10) ? ($incomingDate . ' ' . date('H:i:s')) : $incomingDate) : date('Y-m-d H:i:s');

            $updateStmt = $conn->prepare("UPDATE restocks SET quantity = :qty, restock_date = :date WHERE id = :id");
            $updateStmt->execute([
                ':qty' => $data->quantity, 
                ':date' => $restockDate,
                ':id' => $data->id
            ]);
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
