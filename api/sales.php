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
                
                // Map database fields to JS expected fields
                $sale['date'] = $sale['sale_date'];
                $sale['customerInfo'] = [
                    'name' => $sale['customer_name'],
                    'id' => $sale['customer_id'],
                    'phone' => $sale['customer_phone'],
                    'email' => $sale['customer_email'],
                    'address' => $sale['customer_address'],
                    'city' => $sale['customer_city']
                ];
                $sale['paymentMethod'] = $sale['payment_method'];
                $sale['deliveryType'] = $sale['delivery_type'];
                $sale['warrantyIncrement'] = (float)($sale['warranty_increment'] ?? 0);
                $sale['user'] = $sale['username'];
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

    $status = $data->status ?? 'completed';

    try {
        $conn->beginTransaction();
        
        // 1. Crear cabecera de venta
        $sql = "INSERT INTO sales (invoice_number, customer_name, customer_id, customer_phone, customer_email, customer_address, customer_city, total, discount, delivery_cost, payment_method, delivery_type, sale_date, user_id, username, status) 
                VALUES (:inv, :name, :cid, :phone, :email, :addr, :city, :total, :disc, :del, :pay, :del_type, :sale_date, :uid, :uname, :status)";
        
        // Verificar si el ID de factura ya existe
        $invoiceNumber = $data->id;
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM sales WHERE invoice_number = :inv");
        $checkStmt->execute([':inv' => $invoiceNumber]);
        
        if ($checkStmt->fetchColumn() > 0) {
            // Generar nuevo ID basado en el último existente
            $maxStmt = $conn->prepare("SELECT invoice_number FROM sales WHERE invoice_number LIKE 'FAC%' ORDER BY LENGTH(invoice_number) DESC, invoice_number DESC LIMIT 1");
            $maxStmt->execute();
            $maxInv = $maxStmt->fetchColumn();
            
            if ($maxInv) {
                // Extraer número y sumar 1
                $num = intval(substr($maxInv, 3)) + 1;
                $invoiceNumber = 'FAC' . str_pad($num, 4, '0', STR_PAD_LEFT);
            } else {
                $invoiceNumber = 'FAC1001';
            }
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':inv' => $invoiceNumber,
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
            ':sale_date' => $data->date ?? date('Y-m-d H:i:s'),
            ':uid' => $_SESSION['user_id'],
            ':uname' => $_SESSION['username'],
            ':status' => $status
        ]);
        
        $saleId = $conn->lastInsertId();
        
        // 2. Insertar items y actualizar inventario
        $itemSql = "INSERT INTO sale_items (sale_id, product_ref, product_name, quantity, unit_price, subtotal, sale_type) VALUES (:sid, :ref, :pname, :qty, :price, :sub, :type)";
        $stockSql = "UPDATE products SET quantity = quantity - :qty WHERE reference = :ref";
        
        $itemStmt = $conn->prepare($itemSql);
        $stockStmt = $conn->prepare($stockSql);
        
        foreach ($data->products as $item) {
            // Insertar item (usando campos del frontend: productId, quantity, unitPrice, subtotal)
            $itemStmt->execute([
                ':sid' => $saleId,
                ':ref' => $item->productId,
                ':pname' => $item->productName,
                ':qty' => $item->quantity,
                ':price' => $item->unitPrice,
                ':sub' => $item->subtotal,
                ':type' => $item->saleType ?? 'retail'
            ]);
            
            // Descontar inventario
            $stockStmt->execute([
                ':qty' => $item->quantity,
                ':ref' => $item->productId
            ]);
        }
        
        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Venta registrada con éxito', 'id' => $invoiceNumber]);

    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Error al procesar la venta: ' . $e->getMessage()]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar Venta (Solo admin)
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

        // 1. Obtener items de la venta para restablecer stock
        $stmt = $conn->prepare("SELECT product_ref, quantity FROM sale_items WHERE sale_id = :id");
        $stmt->execute([':id' => $id]);
        $items = $stmt->fetchAll();

        $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity + :qty WHERE reference = :ref");
        foreach ($items as $item) {
            $stockStmt->execute([
                ':qty' => $item['quantity'],
                ':ref' => $item['product_ref']
            ]);
        }

        // 2. Eliminar la venta (la eliminación en cascada se encargará de sale_items)
        $deleteStmt = $conn->prepare("DELETE FROM sales WHERE id = :id");
        $deleteStmt->execute([':id' => $id]);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Venta eliminada y stock restablecido']);
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'PUT') {
    // Editar Venta (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));
    if (!$data || !isset($data->id)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    try {
        // 1. Obtener la venta actual para tener los valores base (subtotal, etc.)
        $stmt = $conn->prepare("SELECT subtotal, delivery_cost, discount, warranty_increment FROM sales WHERE id = :id");
        $stmt->execute([':id' => $data->id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            http_response_code(404);
            echo json_encode(['error' => 'Venta no encontrada']);
            exit;
        }

        // 2. Preparar valores (usar los nuevos si vienen, o los viejos)
        $warrantyIncrement = isset($data->warrantyIncrement) ? (float)$data->warrantyIncrement : (float)$sale['warranty_increment'];
        $subtotal = isset($data->subtotal) ? (float)$data->subtotal : (float)$sale['subtotal'];
        $deliveryCost = isset($data->deliveryCost) ? (float)$data->deliveryCost : (float)$sale['delivery_cost'];
        $discount = isset($data->discount) ? (float)$data->discount : (float)$sale['discount'];
        
        // Recalcular total: total = subtotal + delivery - discount + warranty
        $newTotal = $subtotal + $deliveryCost - $discount + $warrantyIncrement;

        $sql = "UPDATE sales SET 
                invoice_number = :inv,
                customer_name = :name, 
                customer_id = :cid, 
                customer_phone = :phone, 
                customer_email = :email, 
                customer_address = :addr, 
                customer_city = :city,
                payment_method = :pay,
                status = :status,
                warranty_increment = :winc,
                subtotal = :sub,
                delivery_cost = :del,
                discount = :disc,
                total = :total,
                sale_date = :date
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':inv' => $data->invoiceNumber ?? $data->invoice_number,
            ':name' => $data->customerName ?? $data->customer_name,
            ':cid' => $data->customerId ?? $data->customer_id,
            ':phone' => $data->customerPhone ?? $data->customer_phone,
            ':email' => $data->customerEmail ?? $data->customer_email,
            ':addr' => $data->customerAddress ?? $data->customer_address,
            ':city' => $data->customerCity ?? $data->customer_city,
            ':pay' => $data->paymentMethod ?? $data->payment_method,
            ':status' => $data->status ?? 'completed',
            ':winc' => $warrantyIncrement,
            ':sub' => $subtotal,
            ':del' => $deliveryCost,
            ':disc' => $discount,
            ':total' => $newTotal,
            ':date' => $data->date ?? date('Y-m-d H:i:s'),
            ':id' => $data->id
        ]);

        echo json_encode(['success' => true, 'message' => 'Venta actualizada']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
