<?php
// api/pending_sales.php
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
    // Listar pendientes
    try {
        $stmt = $conn->query("SELECT * FROM sales WHERE status = 'pending' ORDER BY sale_date DESC");
        $pending = $stmt->fetchAll();
        
        // Para cada venta, obtener breve info de productos para mostrar en tabla
        foreach ($pending as &$sale) {
            $itemStmt = $conn->prepare("SELECT product_name, quantity FROM sale_items WHERE sale_id = :id");
            $itemStmt->execute([':id' => $sale['id']]);
            $items = $itemStmt->fetchAll();
            $sale['products'] = $items; 
            // Formato JS esperado: array de {productName: '...', ...}
            
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
            $sale['user'] = $sale['username'];
        }
        
        echo json_encode($pending);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Confirmar pago (POST con action='confirm') O registrar pendiente (si es normal)
    // El JS actual llama a `processSale` que guarda en localStorage.
    // Aquí implementaremos:
    // 1. Crear pendiente (similar a POST sales.php pero status='pending')
    // 2. Confirmar pendiente (UPDATE status='completed')
    // 3. Cancelar pendiente (DELETE o UPDATE status='cancelled')
    
    $data = json_decode(file_get_contents("php://input"));
    $action = $data->action ?? 'create';
    
    if ($action === 'create') {
        // Lógica CREATE PENDING (Similar a sales.php create, pero NO descuenta inventario aun? 
        // Normalmente se aparta la mercancía. Vamos a descontar inventario DE UNA VEZ para apartarlo.
        // Si se cancela, se devuelve.)
        
        try {
            $conn->beginTransaction();
            
             // 1. Crear cabecera
            $sql = "INSERT INTO sales (invoice_number, customer_name, customer_id, customer_phone, customer_email, customer_address, customer_city, total, discount, delivery_cost, payment_method, delivery_type, sale_date, user_id, username, status) 
            VALUES (:inv, :name, :cid, :phone, :email, :addr, :city, :total, :disc, :del, :pay, :del_type, :sale_date, :uid, :uname, 'pending')";
    
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':inv' => $data->id,
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
                ':uname' => $_SESSION['username']
            ]);
            
            $saleId = $conn->lastInsertId();
            
            // 2. Items y Apartado de Inventario
            $itemSql = "INSERT INTO sale_items (sale_id, product_ref, product_name, quantity, unit_price, subtotal, sale_type) VALUES (:sid, :ref, :pname, :qty, :price, :sub, :type)";
            $stockSql = "UPDATE products SET quantity = quantity - :qty WHERE reference = :ref";
            
            $itemStmt = $conn->prepare($itemSql);
            $stockStmt = $conn->prepare($stockSql);
            
            foreach ($data->products as $item) {
                $itemStmt->execute([
                    ':sid' => $saleId,
                    ':ref' => $item->id,
                    ':pname' => $item->name ?? $item->productName,
                    ':qty' => $item->count,
                    ':price' => $item->price,
                    ':sub' => $item->total,
                    ':type' => $item->saleType ?? 'retail'
                ]);
                $stockStmt->execute([
                    ':qty' => $item->count,
                    ':ref' => $item->id
                ]);
            }
            
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Venta pendiente registrada']);

        } catch (PDOException $e) {
            $conn->rollBack();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        
    } elseif ($action === 'confirm') {
        // Confirmar pago (Solo admin)
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }
        
        $saleId = $data->sale_id; // Debe enviarse el ID interno (INT) o la invoice (VARCHAR). Usemos invoice desde JS (o mapear). 
        // JS usa IDs tipo "1001Pending". En DB el invoice almacena eso.
        
        try {
            $stmt = $conn->prepare("UPDATE sales SET status = 'completed' WHERE invoice_number = :inv OR id = :id");
            $stmt->execute([':inv' => $saleId, ':id' => $saleId]);
            echo json_encode(['success' => true, 'message' => 'Venta confirmada']);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

    } elseif ($action === 'cancel') {
        // Cancelar y devolver inventario (Solo admin)
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso denegado']);
            exit;
        }
        
        $saleId = $data->sale_id; // Invoice number probablemente
        
        try {
            $conn->beginTransaction();
            
            // Obtener venta
            $stmt = $conn->prepare("SELECT id FROM sales WHERE invoice_number = :inv OR id = :id");
            $stmt->execute([':inv' => $saleId, ':id' => $saleId]);
            $sale = $stmt->fetch();
            
            if ($sale) {
                $dbId = $sale['id'];
                
                // Devolver inventario
                $itemsStmt = $conn->prepare("SELECT product_ref, quantity FROM sale_items WHERE sale_id = :sid");
                $itemsStmt->execute([':sid' => $dbId]);
                $items = $itemsStmt->fetchAll();
                
                $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity + :qty WHERE reference = :ref");
                
                foreach ($items as $item) {
                    $stockStmt->execute([':qty' => $item['quantity'], ':ref' => $item['product_ref']]);
                }
                
                // Marcar como cancelada (o borrar)
                $updateStmt = $conn->prepare("UPDATE sales SET status = 'cancelled' WHERE id = :id");
                $updateStmt->execute([':id' => $dbId]);
                
                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Venta cancelada e inventario restaurado']);
            } else {
                throw new Exception("Venta no encontrada");
            }
            
        } catch (Exception $e) {
            $conn->rollBack();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
