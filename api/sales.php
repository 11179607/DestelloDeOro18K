<?php
// api/sales.php
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
    // Listar ventas o detalles
    $saleId = $_GET['id'] ?? null;
    
    if ($saleId) {
        // Detalles de una venta
        try {
            $stmt = $conn->prepare("SELECT si.*, p.name as product_name FROM sale_items si LEFT JOIN products p ON si.product_ref = p.reference WHERE si.sale_id = :id");
            $stmt->execute([':id' => $saleId]);
            $items = $stmt->fetchAll();
            echo json_encode($items);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        // Historial de ventas
        try {
            // Filtrar por mes/año si se proporciona
            $month = $_GET['month'] ?? null;
            $year = $_GET['year'] ?? null;
            
            $sql = "SELECT * FROM sales WHERE status = 'completed'";
            $params = [];
            
            if ($month !== null && $year !== null) {
                // SQL month is 1-based, JS is 0-based usually. Let's assume passed as 1-based or handle JS logic.
                // Using JS convention (0-11) + 1 for SQL
                $month = intval($month) + 1;
                $sql .= " AND MONTH(sale_date) = :month AND YEAR(sale_date) = :year";
                $params[':month'] = $month;
                $params[':year'] = $year;
            }
            
            $sql .= " ORDER BY sale_date DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $sales = $stmt->fetchAll();

            // Cargar items para cada venta (necesario para cálculos de ganancia en el frontend)
            foreach ($sales as &$sale) {
                $itemStmt = $conn->prepare("SELECT si.*, p.purchase_price, p.name as product_name 
                                           FROM sale_items si 
                                           LEFT JOIN products p ON si.product_ref = p.reference 
                                           WHERE si.sale_id = :id");
                $itemStmt->execute([':id' => $sale['id']]);
                $sale['products'] = $itemStmt->fetchAll();
                
                // Formatear para compatibilidad con el JS existente (que espera 'unitPrice' y 'productName')
                foreach ($sale['products'] as &$item) {
                    $item['productId'] = $item['product_ref'];
                    $item['productName'] = $item['product_name'];
                    $item['unitPrice'] = (float)$item['unit_price'];
                    $item['quantity'] = (int)$item['quantity'];
                    $item['subtotal'] = (float)$item['subtotal'];
                    $item['purchasePrice'] = (float)($item['purchase_price'] ?? 0);
                }
            }

            echo json_encode($sales);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

} elseif ($method === 'POST') {
    // Registrar Venta
    $data = json_decode(file_get_contents("php://input"));
    
    if (!$data || !isset($data->products) || !is_array($data->products)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos']);
        exit;
    }

    try {
        $conn->beginTransaction();
        
        // 1. Crear cabecera de venta
        $sql = "INSERT INTO sales (invoice_number, customer_name, customer_id, customer_phone, customer_email, customer_address, customer_city, total, discount, delivery_cost, payment_method, delivery_type, user_id, username, status) 
                VALUES (:inv, :name, :cid, :phone, :email, :addr, :city, :total, :disc, :del, :pay, :del_type, :uid, :uname, 'completed')";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':inv' => $data->id, // ID de factura generado en JS
            ':name' => $data->customerInfo->name,
            ':cid' => $data->customerInfo->id,
            ':phone' => $data->customerInfo->phone,
            ':email' => $data->customerInfo->email ?? '',
            ':addr' => $data->customerInfo->address,
            ':city' => $data->customerInfo->city,
            ':total' => $data->total,
            ':disc' => $data->discount ?? 0,
            ':del' => $data->deliveryCost ?? 0,
            ':pay' => $data->paymentMethod,
            ':del_type' => $data->deliveryType,
            ':uid' => $_SESSION['user_id'],
            ':uname' => $_SESSION['username']
        ]);
        
        $saleId = $conn->lastInsertId();
        
        // 2. Insertar items y actualizar inventario
        $itemSql = "INSERT INTO sale_items (sale_id, product_ref, product_name, quantity, unit_price, subtotal, sale_type) VALUES (:sid, :ref, :pname, :qty, :price, :sub, :type)";
        $stockSql = "UPDATE products SET quantity = quantity - :qty WHERE reference = :ref";
        
        $itemStmt = $conn->prepare($itemSql);
        $stockStmt = $conn->prepare($stockSql);
        
        foreach ($data->products as $item) {
            // Insertar item
            $itemStmt->execute([
                ':sid' => $saleId,
                ':ref' => $item->id,
                ':pname' => $item->name ?? $item->productName,
                ':qty' => $item->count,
                ':price' => $item->price,
                ':sub' => $item->total,
                ':type' => $item->saleType ?? 'retail'
            ]);
            
            // Descontar inventario
            $stockStmt->execute([
                ':qty' => $item->count,
                ':ref' => $item->id
            ]);
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Venta registrada con éxito']);

    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Error al procesar la venta: ' . $e->getMessage()]);
    }
}
?>
