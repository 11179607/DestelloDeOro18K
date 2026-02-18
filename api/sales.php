<?php
// api/sales.php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once '../config/db.php';
require_once 'logger.php';

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
            $stmt = $conn->prepare("SELECT si.*, p.name AS product_name FROM sale_items si LEFT JOIN products p ON si.product_ref = p.reference WHERE si.sale_id = :id");
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
            $month = $_GET['month'] ?? null;
            $year  = $_GET['year'] ?? null;

            $sql    = "SELECT * FROM sales WHERE status IN ('completed', 'pending')";
            $params = [];

            if ($month !== null && $year !== null) {
                // JS envía month 0-11; SQL 1-12
                $month = intval($month) + 1;
                $sql  .= " AND MONTH(sale_date) = :month AND YEAR(sale_date) = :year";
                $params[':month'] = $month;
                $params[':year']  = $year;
            }

            $sql .= " ORDER BY sale_date DESC";

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            $sales = $stmt->fetchAll();

            // Cargar items para cada venta
            foreach ($sales as &$sale) {
                $itemStmt = $conn->prepare("
                    SELECT si.*, p.purchase_price, p.name AS product_name
                    FROM sale_items si
                    LEFT JOIN products p ON si.product_ref = p.reference
                    WHERE si.sale_id = :id
                ");
                $itemStmt->execute([':id' => $sale['id']]);
                $sale['products'] = $itemStmt->fetchAll();

                foreach ($sale['products'] as &$item) {
                    $item['productId']     = $item['product_ref'];
                    $item['productName']   = $item['product_name'];
                    $item['unitPrice']     = (float)$item['unit_price'];
                    $item['quantity']      = (int)$item['quantity'];
                    $item['subtotal']      = (float)$item['subtotal'];
                    $item['purchasePrice'] = (float)($item['purchase_price'] ?? 0);
                    $item['saleType']      = $item['sale_type'] ?? 'retail';
                }

                // Mapeo a formato esperado por el frontend
                $sale['date'] = $sale['sale_date'];
                $sale['customerInfo'] = [
                    'name'    => $sale['customer_name'],
                    'id'      => $sale['customer_id'],
                    'phone'   => $sale['customer_phone'],
                    'email'   => $sale['customer_email'],
                    'address' => $sale['customer_address'],
                    'city'    => $sale['customer_city']
                ];
                $sale['paymentMethod']   = $sale['payment_method'];
                $sale['deliveryType']    = $sale['delivery_type'];
                $sale['deliveryCost']    = (float)($sale['delivery_cost'] ?? 0);
                $sale['warrantyIncrement'] = (float)($sale['warranty_increment'] ?? 0);
                $sale['user']            = $sale['username'];
                $sale['saleType']        = (!empty($sale['products'])) ? $sale['products'][0]['saleType'] : 'retail';
            }

            echo json_encode($sales);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

} elseif ($method === 'POST') {
    // Registrar Venta
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput);

    // Validación mejorada de datos de entrada
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos: JSON mal formado']);
        exit;
    }

    if (!isset($data->products)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos: falta el campo "products"']);
        exit;
    }

    if (!is_array($data->products)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos: "products" debe ser un array']);
        exit;
    }

    if (empty($data->products)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos inválidos: debe incluir al menos un producto']);
        exit;
    }

    // Validar campos requeridos
    $requiredFields = ['id', 'customerInfo', 'paymentMethod', 'deliveryType', 'total'];
    foreach ($requiredFields as $field) {
        if (!isset($data->$field)) {
            http_response_code(400);
            echo json_encode(['error' => "Campo requerido faltante: $field"]);
            exit;
        }
    }

    // Validar información del cliente
    if (!isset($data->customerInfo->name) || empty(trim($data->customerInfo->name))) {
        http_response_code(400);
        echo json_encode(['error' => 'El nombre del cliente es requerido']);
        exit;
    }

    if (!isset($data->customerInfo->id) || empty(trim($data->customerInfo->id))) {
        http_response_code(400);
        echo json_encode(['error' => 'La cédula del cliente es requerida']);
        exit;
    }

    if (!isset($data->customerInfo->phone) || empty(trim($data->customerInfo->phone))) {
        http_response_code(400);
        echo json_encode(['error' => 'El teléfono del cliente es requerido']);
        exit;
    }

    if (!isset($data->customerInfo->address) || empty(trim($data->customerInfo->address))) {
        http_response_code(400);
        echo json_encode(['error' => 'La dirección del cliente es requerida']);
        exit;
    }

    if (!isset($data->customerInfo->city) || empty(trim($data->customerInfo->city))) {
        http_response_code(400);
        echo json_encode(['error' => 'La ciudad del cliente es requerida']);
        exit;
    }

    $status = $data->status ?? 'completed';

    try {
        $conn->beginTransaction();

        // Verificar si el ID de factura ya existe
        $invoiceNumber = $data->id;
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM sales WHERE invoice_number = :inv");
        $checkStmt->execute([':inv' => $invoiceNumber]);

        if ($checkStmt->fetchColumn() > 0) {
            $conn->rollBack();
            http_response_code(409);
            echo json_encode(['error' => 'El número de factura ya existe. Usa un ID único.']);
            exit;
        }

        // Validar que todos los productos existan y tengan stock suficiente
        foreach ($data->products as $index => $item) {
            if (!isset($item->productId) || empty($item->productId)) {
                $conn->rollBack();
                http_response_code(400);
                echo json_encode(['error' => "Producto #" . ($index + 1) . ": falta la referencia del producto"]);
                exit;
            }

            if (!isset($item->quantity) || $item->quantity <= 0) {
                $conn->rollBack();
                http_response_code(400);
                echo json_encode(['error' => "Producto #" . ($index + 1) . " ({$item->productId}): cantidad inválida"]);
                exit;
            }

            // Verificar que el producto existe en la base de datos
            $productStmt = $conn->prepare("SELECT reference, name, quantity FROM products WHERE reference = :ref");
            $productStmt->execute([':ref' => $item->productId]);
            $product = $productStmt->fetch();

            if (!$product) {
                $conn->rollBack();
                http_response_code(404);
                echo json_encode(['error' => "Producto no encontrado: {$item->productId}. Verifica que el producto existe en el inventario."]);
                exit;
            }

            // Verificar stock suficiente
            if ($product['quantity'] < $item->quantity) {
                $conn->rollBack();
                http_response_code(400);
                echo json_encode(['error' => "Stock insuficiente para '{$product['name']}'. Disponible: {$product['quantity']}, Solicitado: {$item->quantity}"]);
                exit;
            }
        }

        // 1. Crear cabecera de venta
        $sql = "INSERT INTO sales (invoice_number, customer_name, customer_id, customer_phone, customer_email, customer_address, customer_city, total, discount, delivery_cost, warranty_increment, payment_method, delivery_type, sale_date, user_id, username, status)
                VALUES (:inv, :name, :cid, :phone, :email, :addr, :city, :total, :disc, :del, :war, :pay, :del_type, :sale_date, :uid, :uname, :status)";

        // Fecha manual (solo fecha) + hora automática
        $incomingDate = $data->date ?? $data->saleDate ?? null;
        if ($incomingDate) {
            // Si viene sólo la fecha, se concatena la hora actual del servidor
            $saleDate = (strlen($incomingDate) === 10) ? ($incomingDate . ' ' . date('H:i:s')) : $incomingDate;
        } else {
            $saleDate = date('Y-m-d H:i:s');
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':inv'      => $invoiceNumber,
            ':name'     => $data->customerInfo->name,
            ':cid'      => $data->customerInfo->id,
            ':phone'    => $data->customerInfo->phone,
            ':email'    => $data->customerInfo->email ?? '',
            ':addr'     => $data->customerInfo->address,
            ':city'     => $data->customerInfo->city,
            ':total'    => $data->total,
            ':disc'     => $data->discount ?? 0,
            ':del'      => $data->deliveryCost ?? 0,
            ':war'      => $data->warrantyIncrement ?? 0,
            ':pay'      => $data->paymentMethod,
            ':del_type' => $data->deliveryType,
            ':sale_date'=> $saleDate,
            ':uid'      => $_SESSION['user_id'],
            ':uname'    => $_SESSION['username'],
            ':status'   => $status
        ]);

        $saleId = $conn->lastInsertId();

        // 2. Insertar items y actualizar inventario
        $itemSql  = "INSERT INTO sale_items (sale_id, product_ref, product_name, quantity, unit_price, subtotal, sale_type) VALUES (:sid, :ref, :pname, :qty, :price, :sub, :type)";
        $stockSql = "UPDATE products SET quantity = quantity - :qty WHERE reference = :ref";

        $itemStmt  = $conn->prepare($itemSql);
        $stockStmt = $conn->prepare($stockSql);

        foreach ($data->products as $item) {
            $itemStmt->execute([
                ':sid'   => $saleId,
                ':ref'   => $item->productId,
                ':pname' => $item->productName,
                ':qty'   => $item->quantity,
                ':price' => $item->unitPrice,
                ':sub'   => $item->subtotal,
                ':type'  => $item->saleType ?? 'retail'
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
        
        // Proporcionar mensaje de error más específico
        $errorMsg = $e->getMessage();
        $userMsg = 'Error al procesar la venta';
        
        // Detectar errores comunes y proporcionar mensajes más útiles
        if (strpos($errorMsg, 'Duplicate entry') !== false) {
            $userMsg = 'Error: Registro duplicado en la base de datos';
        } elseif (strpos($errorMsg, 'foreign key constraint') !== false) {
            $userMsg = 'Error: Referencia inválida en la base de datos';
        } elseif (strpos($errorMsg, 'Data too long') !== false) {
            $userMsg = 'Error: Uno de los campos excede el tamaño máximo permitido';
        } elseif (strpos($errorMsg, 'Incorrect') !== false) {
            $userMsg = 'Error: Formato de datos incorrecto';
        } elseif (strpos($errorMsg, 'Connection') !== false || strpos($errorMsg, 'connect') !== false) {
            $userMsg = 'Error de conexión con la base de datos. Verifica tu conexión a internet.';
        }
        
        echo json_encode([
            'error' => $userMsg,
            'details' => $errorMsg,
            'help' => 'Si el problema persiste, contacta al administrador del sistema'
        ]);
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
        $stmt = $conn->prepare("SELECT id, invoice_number, total, customer_name FROM sales WHERE id = :id OR invoice_number = :id");
        $stmt->execute([':id' => $id]);
        $saleToDelete = $stmt->fetch();

        if (!$saleToDelete) {
            http_response_code(404);
            echo json_encode(['error' => 'Venta no encontrada']);
            exit;
        }

        $dbId   = $saleToDelete['id'];
        $details = "Venta #" . ($saleToDelete['invoice_number'] ?? $dbId) . " eliminada. Cliente: " . ($saleToDelete['customer_name'] ?? 'N/A') . ". Total: " . ($saleToDelete['total'] ?? 0);

        $conn->beginTransaction();

        // Devolver inventario
        $stmt = $conn->prepare("SELECT product_ref, quantity FROM sale_items WHERE sale_id = :id");
        $stmt->execute([':id' => $dbId]);
        $items = $stmt->fetchAll();

        $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity + :qty WHERE reference = :ref");
        foreach ($items as $item) {
            $stockStmt->execute([
                ':qty' => $item['quantity'],
                ':ref' => $item['product_ref']
            ]);
        }

        // Eliminar venta
        $deleteStmt = $conn->prepare("DELETE FROM sales WHERE id = :id");
        $deleteStmt->execute([':id' => $dbId]);

        logAction($conn, $_SESSION['username'], 'DELETE', 'SALE', $dbId, $details);

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Venta eliminada y stock restablecido']);
    } catch (PDOException $e) {
        $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($method === 'PUT') {
    // Editar / confirmar venta (Solo admin)
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
        $stmt = $conn->prepare("SELECT * FROM sales WHERE id = :id OR invoice_number = :id");
        $stmt->execute([':id' => $data->id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            http_response_code(404);
            echo json_encode(['error' => 'Venta no encontrada (ID: ' . $data->id . ')']);
            exit;
        }

        $dbId     = $sale['id'];
        $oldStatus = $sale['status'];

        // Fallback a valores actuales
        $invoiceNumber   = $data->invoiceNumber ?? $data->id ?? $sale['invoice_number'];
        $customerName    = $data->customerName   ?? $sale['customer_name'];
        $customerId      = $data->customerId     ?? $sale['customer_id'];
        $customerPhone   = $data->customerPhone  ?? $sale['customer_phone'];
        $customerEmail   = $data->customerEmail  ?? $sale['customer_email'];
        $customerAddress = $data->customerAddress?? $sale['customer_address'];
        $customerCity    = $data->customerCity   ?? $sale['customer_city'];
        $paymentMethod   = $data->paymentMethod  ?? $sale['payment_method'];
        $incomingDate    = $data->saleDate ?? $data->date ?? null;
        if ($incomingDate) {
            $saleDate = (strlen($incomingDate) === 10) ? ($incomingDate . ' ' . date('H:i:s')) : $incomingDate;
        } else {
            $saleDate = $sale['sale_date'];
        }

        $deliveryCost      = isset($data->deliveryCost)      ? (float)$data->deliveryCost      : (float)$sale['delivery_cost'];
        $discount          = isset($data->discount)          ? (float)$data->discount          : (float)$sale['discount'];
        $warrantyIncrement = isset($data->warrantyIncrement) ? (float)$data->warrantyIncrement : (float)($sale['warranty_increment'] ?? 0);

        $currentSubtotal  = (float)$sale['total'] - (float)$sale['delivery_cost'] + (float)$sale['discount'] - (float)($sale['warranty_increment'] ?? 0);
        $incomingSubtotal = isset($data->subtotal) ? (float)$data->subtotal : $currentSubtotal;
        $subtotal         = ($incomingSubtotal > 0) ? $incomingSubtotal : $currentSubtotal;

        $newStatus = $data->status ?? $sale['status'];
        $newTotal  = $subtotal + $deliveryCost - $discount + $warrantyIncrement;

        $sql = "UPDATE sales SET
                invoice_number     = :inv,
                customer_name      = :name,
                customer_id        = :cid,
                customer_phone     = :phone,
                customer_email     = :email,
                customer_address   = :addr,
                customer_city      = :city,
                payment_method     = :pay,
                status             = :status,
                delivery_cost      = :del,
                warranty_increment = :war,
                discount           = :disc,
                total              = :total,
                sale_date          = :date
                WHERE id = :dbId";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':inv'   => $invoiceNumber,
            ':name'  => $customerName,
            ':cid'   => $customerId,
            ':phone' => $customerPhone,
            ':email' => $customerEmail,
            ':addr'  => $customerAddress,
            ':city'  => $customerCity,
            ':pay'   => $paymentMethod,
            ':status'=> $newStatus,
            ':del'   => $deliveryCost,
            ':war'   => $warrantyIncrement,
            ':disc'  => $discount,
            ':total' => $newTotal,
            ':date'  => $saleDate ?: date('Y-m-d H:i:s'),
            ':dbId'  => $dbId
        ]);

        $actionType = 'EDIT';
        $details    = "Venta #{$invoiceNumber} actualizada.";

        if ($oldStatus === 'pending' && $newStatus === 'completed') {
            $actionType = 'CONFIRM_PAYMENT';
            $details    = "Pago confirmado para venta #{$invoiceNumber}.";
        }

        logAction($conn, $_SESSION['username'], $actionType, 'SALE', $dbId, $details);

        echo json_encode(['success' => true, 'message' => 'Venta actualizada']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
