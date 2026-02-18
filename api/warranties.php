<?php
// api/warranties.php
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
    // Listar garantías
    try {
        $month = $_GET['month'] ?? null;
        $year = $_GET['year'] ?? null;
        
        $sql = "SELECT * FROM warranties";
        $params = [];
        
        if ($month !== null && $year !== null) {
            $month = intval($month) + 1;
            $sql .= " WHERE MONTH(created_at) = :month AND YEAR(created_at) = :year";
            $params[':month'] = $month;
            $params[':year'] = $year;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $warranties = $stmt->fetchAll();
        
        // Map database fields to JS expected fields
        foreach ($warranties as &$warranty) {
            // Fecha: mapear created_at a 'date' para consistencia con otras tablas
            $warranty['date'] = $warranty['created_at'];
            $warranty['createdAt'] = $warranty['created_at'];
            $warranty['endDate'] = $warranty['end_date'] ?? null;
            
            // Cliente
            $warranty['customerName'] = $warranty['customer_name'];
            
            // Venta original
            $warranty['originalSaleId'] = $warranty['original_invoice_id'];
            
            // Producto original
            $warranty['originalProductId'] = $warranty['product_ref'];
            $warranty['originalProductName'] = $warranty['product_name'];
            
            // Producto de reemplazo (si aplica)
            $warranty['productType'] = $warranty['product_type'];
            $warranty['newProductRef'] = $warranty['new_product_ref'];
            $warranty['newProductName'] = $warranty['new_product_name'];
            $warranty['quantity'] = intval($warranty['quantity'] ?? 1);
            
            // Motivo de garantía
            $warranty['warrantyReason'] = $warranty['reason'];
            $warranty['warrantyReasonText'] = $warranty['reason'];
            
            // Costos
            $warranty['totalCost'] = (float)($warranty['total_cost'] ?? 0);
            $warranty['additionalValue'] = (float)($warranty['additional_value'] ?? 0);
            $warranty['shippingValue'] = (float)($warranty['shipping_value'] ?? 0);
            
            // Usuario
            $warranty['user'] = $warranty['username'] ?? 'admin';
            $warranty['createdBy'] = $warranty['username'] ?? 'admin';
        }
        
        echo json_encode($warranties);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Registrar Garantía (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));
    
    // Mapeo de datos JS a DB
    // JS envía: originalSaleId, customerName, originalProductId, originalProductName, warrantyReason, notes, productType, newProductRef...
    
    try {
        // Asegurar columnas (end_date y username)
        try {
             $conn->exec("ALTER TABLE warranties ADD COLUMN IF NOT EXISTS end_date DATE AFTER reason");
             $conn->exec("ALTER TABLE warranties ADD COLUMN IF NOT EXISTS username VARCHAR(50) AFTER user_id");
             $conn->exec("ALTER TABLE warranties ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
             $conn->exec("ALTER TABLE warranties ADD COLUMN IF NOT EXISTS updated_by VARCHAR(50)");
             $conn->exec("ALTER TABLE warranties ADD COLUMN IF NOT EXISTS quantity INT DEFAULT 1 AFTER product_name");
             // Asegurar columna en tabla de gastos
             $conn->exec("ALTER TABLE expenses ADD COLUMN IF NOT EXISTS warranty_id INT DEFAULT NULL");
        } catch(Exception $e) {}

        // CORREGIDO: Cambiar :qty_val por :quantity para que coincida con el nombre de la columna
        $sql = "INSERT INTO warranties (
            sale_id, original_invoice_id, customer_name, product_ref, product_name, quantity, reason, end_date, notes,
            product_type, new_product_ref, new_product_name, additional_value, shipping_value, total_cost,
            status, user_id, username, created_at
        ) VALUES (
            :sid, :inv, :cust, :pref, :pname, :quantity, :reason, :edate, :notes,
            :ptype, :npref, :npname, :addval, :shipval, :total,
            :status, :uid, :uname, :created_at
        )";
        
        // Buscar ID de venta si es posible
        $saleIdInt = null;
        if (isset($data->originalSaleId)) {
            $sStmt = $conn->prepare("SELECT id FROM sales WHERE id = :inv OR invoice_number = :inv LIMIT 1");
            $sStmt->execute([':inv' => $data->originalSaleId]);
            $sRow = $sStmt->fetch();
            if ($sRow) $saleIdInt = $sRow['id'];
        }
        
        $totalCost = ($data->additionalValue ?? 0) + ($data->shippingValue ?? 0);

        $incomingCreated = $data->date ?? $data->createdAt ?? null;
        $createdAt = $incomingCreated ? ((strlen($incomingCreated) === 10) ? ($incomingCreated . ' ' . date('H:i:s')) : $incomingCreated) : date('Y-m-d H:i:s');

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':sid' => $saleIdInt,
            ':inv' => $data->originalSaleId ?? '',
            ':cust' => $data->customerName ?? '',
            ':pref' => $data->originalProductId ?? '',
            ':pname' => $data->originalProductName ?? '',
            ':quantity' => $data->quantity ?? 1,  // CORREGIDO: Cambiado de :qty_val a :quantity
            ':reason' => $data->warrantyReason ?? '',
            ':edate' => $data->endDate ?? null,
            ':notes' => $data->notes ?? '',
            ':ptype' => $data->productType ?? 'same',
            ':npref' => $data->newProductRef ?? null,
            ':npname' => $data->newProductName ?? null,
            ':addval' => $data->additionalValue ?? 0,
            ':shipval' => $data->shippingValue ?? 0,
            ':total' => $totalCost,
            ':status' => $data->status ?? 'pending',
            ':uid' => $_SESSION['user_id'],
            ':uname' => $_SESSION['username'],
            ':created_at' => $createdAt
        ]);
        
        $warrantyId = $conn->lastInsertId();

        // 2. Actualizar warranty_increment en la venta original si hay additionalValue
        $additionalValue = $data->additionalValue ?? 0;
        if ($additionalValue > 0 && $saleIdInt) {
            // Obtener datos actuales de la venta
            $saleStmt = $conn->prepare("SELECT warranty_increment, total, delivery_cost, discount FROM sales WHERE id = :id");
            $saleStmt->execute([':id' => $saleIdInt]);
            $saleData = $saleStmt->fetch();
            
            if ($saleData) {
                // Calcular nuevo warranty_increment (acumulativo si ya había garantías previas)
                $currentWarrantyIncrement = (float)($saleData['warranty_increment'] ?? 0);
                $newWarrantyIncrement = $currentWarrantyIncrement + $additionalValue;
                
                // Recalcular total de la venta
                // total = subtotal + delivery_cost - discount + warranty_increment
                // subtotal = total_actual - delivery_cost + discount - warranty_increment_actual
                $currentTotal = (float)$saleData['total'];
                $deliveryCost = (float)$saleData['delivery_cost'];
                $discount = (float)$saleData['discount'];
                $subtotal = $currentTotal - $deliveryCost + $discount - $currentWarrantyIncrement;
                $newTotal = $subtotal + $deliveryCost - $discount + $newWarrantyIncrement;
                
                // Actualizar venta
                $updateSaleStmt = $conn->prepare("UPDATE sales SET warranty_increment = :war, total = :total WHERE id = :id");
                $updateSaleStmt->execute([
                    ':war' => $newWarrantyIncrement,
                    ':total' => $newTotal,
                    ':id' => $saleIdInt
                ]);
                
                logAction($conn, $_SESSION['username'], 'WARRANTY_INCREMENT_UPDATED', 'SALE', $saleIdInt, "Incremento por garantía actualizado: +" . formatMoney($additionalValue) . ". Nuevo total: " . formatMoney($newTotal));
            }
        }

        // 3. Lógica de deducción de stock si se crea como completada
        if (($data->status ?? 'pending') === 'completed') {
            $productRef = $data->newProductRef ?? ($data->originalProductId ?? '');
            $qtyToDeduct = $data->quantity ?? 1;
            if ($productRef) {
                // Descontar inventario (usando la columna correcta: reference)
                $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity - :qty WHERE reference = :ref AND quantity >= :qty");
                $stockStmt->execute([':ref' => $productRef, ':qty' => $qtyToDeduct]);
                
                if ($stockStmt->rowCount() > 0) {
                    logAction($conn, $_SESSION['username'], 'WARRANTY_CREATED_COMPLETED', 'WARRANTY', $warrantyId, "Garantía creada como completada. Stock descontado ($qtyToDeduct unidades) para REF: $productRef");
                }
            }
        } else {
            logAction($conn, $_SESSION['username'], 'WARRANTY_CREATED', 'WARRANTY', $warrantyId, "Garantía registrada para Factura: " . ($data->originalSaleId ?? 'N/A'));
        }
        
        // 4. Registrar valor de envío como gasto (NUEVA LÓGICA)
        $shippingValue = (float)($data->shippingValue ?? 0);
        if ($shippingValue > 0) {
            $expenseDesc = "Gasto de Envío por Garantía #" . $warrantyId . " - Cliente: " . ($data->customerName ?? 'N/A');
            $expStmt = $conn->prepare("INSERT INTO expenses (description, amount, expense_date, user_id, username, warranty_id) VALUES (:desc, :amt, :date, :uid, :uname, :wid)");
            $expStmt->execute([
                ':desc' => $expenseDesc,
                ':amt' => $shippingValue,
                ':date' => $createdAt,
                ':uid' => $_SESSION['user_id'],
                ':uname' => $_SESSION['username'],
                ':wid' => $warrantyId
            ]);
            logAction($conn, $_SESSION['username'], 'WARRANTY_SHIPPING_EXPENSE', 'EXPENSE', $conn->lastInsertId(), "Gasto de envío registrado por garantía #$warrantyId: " . formatMoney($shippingValue));
        }
        
        // Helper function para formatear dinero en logs
        function formatMoney($value) {
            return '$' . number_format($value, 0, ',', '.');
        }
        
        echo json_encode(['success' => true, 'message' => 'Garantía registrada', 'id' => $warrantyId]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error de DB: ' . $e->getMessage()]);
    }

} elseif ($method === 'PUT') {
    // Actualizar estado (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));
    
    if (!isset($data->id) || !isset($data->status)) {
         http_response_code(400);
         echo json_encode(['error' => 'Datos incompletos']);
         exit;
    }
    
    try {
        // 1. Obtener estado actual y datos de la garantía
        $checkStmt = $conn->prepare("SELECT status, new_product_ref, product_type, product_ref, sale_id, additional_value FROM warranties WHERE id = :id");
        $checkStmt->execute([':id' => $data->id]);
        $currentWarranty = $checkStmt->fetch();

        if (!$currentWarranty) {
            http_response_code(404);
            echo json_encode(['error' => 'Garantía no encontrada']);
            exit;
        }

        $oldStatus = $currentWarranty['status'];
        $newStatus = $data->status ?? 'pending';
        // El productRef a descontar es el nuevo si existe, sino el original
        $productRef = $data->newProductRef ?? $currentWarranty['new_product_ref'];
        if (!$productRef) {
            $productRef = $currentWarranty['product_ref'];
        }
        $qtyToDeduct = $data->quantity ?? $currentWarranty['quantity'] ?? 1;

        $conn->beginTransaction();

        $incomingCreated = $data->date ?? $data->createdAt ?? null;
        $createdAt = $incomingCreated ? ((strlen($incomingCreated) === 10) ? ($incomingCreated . ' ' . date('H:i:s')) : $incomingCreated) : $currentWarranty['created_at'];

        $sql = "UPDATE warranties SET 
                reason = :reason, 
                notes = :notes, 
                product_type = :ptype, 
                new_product_ref = :npref, 
                new_product_name = :npname, 
                quantity = :qty_val, 
                additional_value = :addval, 
                shipping_value = :shipval, 
                total_cost = :total, 
                status = :status,
                created_at = :created_at,
                updated_at = NOW(),
                updated_by = :uby
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':reason' => $data->warrantyReason ?? null,
            ':notes' => $data->notes ?? null,
            ':ptype' => $data->productType ?? 'same',
            ':npref' => $data->newProductRef ?? $productRef,
            ':npname' => $data->newProductName ?? null,
            ':qty_val' => $data->quantity ?? 1,
            ':addval' => $data->additionalValue ?? 0,
            ':shipval' => $data->shippingValue ?? 0,
            ':total' => ($data->additionalValue ?? 0) + ($data->shippingValue ?? 0),
            ':status' => $newStatus,
            ':created_at' => $createdAt,
            ':uby' => $_SESSION['username'],
            ':id' => $data->id
        ]);

        // 2. Actualizar warranty_increment en la venta original si cambió el additionalValue
        $oldAdditionalValue = (float)($currentWarranty['additional_value'] ?? 0);
        $newAdditionalValue = (float)($data->additionalValue ?? 0);
        $saleIdInt = $currentWarranty['sale_id'];
        
        if ($saleIdInt && ($newAdditionalValue != $oldAdditionalValue)) {
            // Obtener datos actuales de la venta
            $saleStmt = $conn->prepare("SELECT warranty_increment, total, delivery_cost, discount FROM sales WHERE id = :id");
            $saleStmt->execute([':id' => $saleIdInt]);
            $saleData = $saleStmt->fetch();
            
            if ($saleData) {
                // Calcular la diferencia (puede ser positiva o negativa)
                $incrementDifference = $newAdditionalValue - $oldAdditionalValue;
                
                // Actualizar warranty_increment (sumar la diferencia)
                $currentWarrantyIncrement = (float)($saleData['warranty_increment'] ?? 0);
                $newWarrantyIncrement = $currentWarrantyIncrement + $incrementDifference;
                
                // Recalcular total de la venta
                $currentTotal = (float)$saleData['total'];
                $deliveryCost = (float)$saleData['delivery_cost'];
                $discount = (float)$saleData['discount'];
                $subtotal = $currentTotal - $deliveryCost + $discount - $currentWarrantyIncrement;
                $newTotal = $subtotal + $deliveryCost - $discount + $newWarrantyIncrement;
                
                // Actualizar venta
                $updateSaleStmt = $conn->prepare("UPDATE sales SET warranty_increment = :war, total = :total WHERE id = :id");
                $updateSaleStmt->execute([
                    ':war' => $newWarrantyIncrement,
                    ':total' => $newTotal,
                    ':id' => $saleIdInt
                ]);
                
                if (!function_exists('formatMoney')) {
                    function formatMoney($value) {
                        return '$' . number_format($value, 0, ',', '.');
                    }
                }
                
                $changeText = $incrementDifference > 0 ? "+" . formatMoney($incrementDifference) : formatMoney($incrementDifference);
                logAction($conn, $_SESSION['username'], 'WARRANTY_INCREMENT_UPDATED', 'SALE', $saleIdInt, "Incremento por garantía actualizado: {$changeText}. Nuevo total: " . formatMoney($newTotal));
            }
        }

        // 3. Lógica de deducción de stock
        // Solo si pasa de cualquier estado a 'completed' y no estaba ya 'completed'
        if ($newStatus === 'completed' && $oldStatus !== 'completed') {
            // Verificar si el producto existe y tiene stock
            $pStmt = $conn->prepare("SELECT quantity, name FROM products WHERE reference = :ref");
            $pStmt->execute([':ref' => $productRef]);
            $product = $pStmt->fetch();

            if ($product) {
                if ($product['quantity'] >= $qtyToDeduct) {
                    $stockStmt = $conn->prepare("UPDATE products SET quantity = quantity - :qty WHERE reference = :ref");
                    $stockStmt->execute([':qty' => $qtyToDeduct, ':ref' => $productRef]);
                    
                    // Registrar en LOG
                    logAction($conn, $_SESSION['username'], 'WARRANTY_COMPLETED', 'WARRANTY', $data->id, "Garantía completada. Stock descontado ($qtyToDeduct unidades) para REF: $productRef");
                } else {
                    // Si no hay stock, registrar advertencia
                    logAction($conn, $_SESSION['username'], 'WARRANTY_WARNING', 'WARRANTY', $data->id, "Garantía completada PERO NO HABÍA STOCK SUFICIENTE ($qtyToDeduct requeridas) para REF: $productRef");
                }
            }
        }

        // 4. Actualizar o Crear Gasto de Envío (NUEVA LÓGICA)
        $shippingValue = (float)($data->shippingValue ?? 0);
        $warrantyId = $data->id;
        
        // Verificar si ya existe un gasto para esta garantía
        $checkExpStmt = $conn->prepare("SELECT id FROM expenses WHERE warranty_id = :wid");
        $checkExpStmt->execute([':wid' => $warrantyId]);
        $existingExpense = $checkExpStmt->fetch();
        
        if ($shippingValue > 0) {
            $expenseDesc = "Gasto de Envío por Garantía #" . $warrantyId . " - Cliente: " . ($data->customerName ?? 'N/A');
            if ($existingExpense) {
                // Actualizar gasto existente
                $upExpStmt = $conn->prepare("UPDATE expenses SET description = :desc, amount = :amt, expense_date = :date WHERE id = :eid");
                $upExpStmt->execute([
                    ':desc' => $expenseDesc,
                    ':amt' => $shippingValue,
                    ':date' => $createdAt,
                    ':eid' => $existingExpense['id']
                ]);
            } else {
                // Crear nuevo gasto
                $insExpStmt = $conn->prepare("INSERT INTO expenses (description, amount, expense_date, user_id, username, warranty_id) VALUES (:desc, :amt, :date, :uid, :uname, :wid)");
                $insExpStmt->execute([
                    ':desc' => $expenseDesc,
                    ':amt' => $shippingValue,
                    ':date' => $createdAt,
                    ':uid' => $_SESSION['user_id'],
                    ':uname' => $_SESSION['username'],
                    ':wid' => $warrantyId
                ]);
            }
        } else if ($existingExpense) {
            // Si el valor de envío es 0 pero había un gasto, eliminarlo
            $delExpStmt = $conn->prepare("DELETE FROM expenses WHERE id = :eid");
            $delExpStmt->execute([':eid' => $existingExpense['id']]);
        }
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar Garantía (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $id = $_GET['id'] ?? null;
    try {
        $conn->beginTransaction();
        
        // 1. Obtener datos de la garantía antes de eliminarla
        $getStmt = $conn->prepare("SELECT sale_id, additional_value FROM warranties WHERE id = :id");
        $getStmt->execute([':id' => $id]);
        $warranty = $getStmt->fetch();
        
        // 2. Si la garantía tenía additionalValue, restar del warranty_increment de la venta
        if ($warranty && $warranty['sale_id'] && ($warranty['additional_value'] ?? 0) > 0) {
            $saleIdInt = $warranty['sale_id'];
            $additionalValue = (float)$warranty['additional_value'];
            
            // Obtener datos actuales de la venta
            $saleStmt = $conn->prepare("SELECT warranty_increment, total, delivery_cost, discount FROM sales WHERE id = :id");
            $saleStmt->execute([':id' => $saleIdInt]);
            $saleData = $saleStmt->fetch();
            
            if ($saleData) {
                // Restar el additionalValue del warranty_increment
                $currentWarrantyIncrement = (float)($saleData['warranty_increment'] ?? 0);
                $newWarrantyIncrement = max(0, $currentWarrantyIncrement - $additionalValue); // No permitir negativos
                
                // Recalcular total de la venta
                $currentTotal = (float)$saleData['total'];
                $deliveryCost = (float)$saleData['delivery_cost'];
                $discount = (float)$saleData['discount'];
                $subtotal = $currentTotal - $deliveryCost + $discount - $currentWarrantyIncrement;
                $newTotal = $subtotal + $deliveryCost - $discount + $newWarrantyIncrement;
                
                // Actualizar venta
                $updateSaleStmt = $conn->prepare("UPDATE sales SET warranty_increment = :war, total = :total WHERE id = :id");
                $updateSaleStmt->execute([
                    ':war' => $newWarrantyIncrement,
                    ':total' => $newTotal,
                    ':id' => $saleIdInt
                ]);
                
                if (!function_exists('formatMoney')) {
                    function formatMoney($value) {
                        return '$' . number_format($value, 0, ',', '.');
                    }
                }
                
                logAction($conn, $_SESSION['username'], 'WARRANTY_INCREMENT_REMOVED', 'SALE', $saleIdInt, "Incremento por garantía eliminado: -" . formatMoney($additionalValue) . ". Nuevo total: " . formatMoney($newTotal));
            }
        }
        
        // 3. Eliminar la garantía y su gasto asociado (NUEVA LÓGICA)
        // El gasto se elimina automáticamente o manualmente aquí
        $delExpStmt = $conn->prepare("DELETE FROM expenses WHERE warranty_id = :wid");
        $delExpStmt->execute([':wid' => $id]);

        $stmt = $conn->prepare("DELETE FROM warranties WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        $conn->commit();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>