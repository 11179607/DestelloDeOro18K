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

// Helper function para formatear dinero en logs
function formatMoney($value) {
    return '$' . number_format((float)$value, 0, ',', '.');
}

/**
 * Sincroniza el cambio de producto de una garantía con los items de la venta original.
 * Esto permite que el historial de ganancias refleje el costo del nuevo producto.
 */
function syncWarrantyWithSaleItems($conn, $saleId, $oldProductRef, $newProductRef, $quantity, $newProductName = null) {
    if (!$saleId || !$oldProductRef || !$newProductRef || $oldProductRef === $newProductRef) {
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM sale_items WHERE sale_id = :sid AND product_ref = :ref LIMIT 1");
        $stmt->execute([':sid' => $saleId, ':ref' => $oldProductRef]);
        $item = $stmt->fetch();

        if ($item) {
            $oldId = $item['id'];
            $oldQty = (int)$item['quantity'];
            $qtyToMove = (int)$quantity;

            if ($oldQty > $qtyToMove) {
                // Caso parcial: reducir cantidad del original e insertar nuevo
                $updateQtyStmt = $conn->prepare("UPDATE sale_items SET quantity = quantity - :moveQty, subtotal = (quantity - :moveQty) * unit_price WHERE id = :id");
                $updateQtyStmt->execute([':moveQty' => $qtyToMove, ':id' => $oldId]);

                // Obtener nombre del nuevo producto si no viene
                if (!$newProductName) {
                    $pStmt = $conn->prepare("SELECT name FROM products WHERE reference = :ref");
                    $pStmt->execute([':ref' => $newProductRef]);
                    $newProductName = $pStmt->fetchColumn() ?: "Producto de Reemplazo";
                }

                $insertStmt = $conn->prepare("INSERT INTO sale_items (sale_id, product_ref, product_name, quantity, unit_price, subtotal, sale_type) 
                                              VALUES (:sid, :ref, :name, :qty, :price, :sub, :type)");
                $insertStmt->execute([
                    ':sid' => $saleId,
                    ':ref' => $newProductRef,
                    ':name' => $newProductName,
                    ':qty' => $qtyToMove,
                    ':price' => $item['unit_price'],
                    ':sub' => $qtyToMove * $item['unit_price'],
                    ':type' => $item['sale_type']
                ]);
            } else {
                // Caso total: simplemente actualizar referencia y nombre
                if (!$newProductName) {
                    $pStmt = $conn->prepare("SELECT name FROM products WHERE reference = :ref");
                    $pStmt->execute([':ref' => $newProductRef]);
                    $newProductName = $pStmt->fetchColumn() ?: "Producto de Reemplazo";
                }

                $updateStmt = $conn->prepare("UPDATE sale_items SET product_ref = :newRef, product_name = :newName WHERE id = :id");
                $updateStmt->execute([
                    ':newRef' => $newProductRef,
                    ':newName' => $newProductName,
                    ':id' => $oldId
                ]);
            }
        }
    } catch (PDOException $e) {
        error_log("Error syncing warranty with sale items: " . $e->getMessage());
    }
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
        } catch(Exception $e) {}

        $sql = "INSERT INTO warranties (
            sale_id, original_invoice_id, customer_name, product_ref, product_name, quantity, reason, end_date, notes,
            product_type, new_product_ref, new_product_name, additional_value, shipping_value, total_cost,
            status, user_id, username, created_at
        ) VALUES (
            :sid, :inv, :cust, :pref, :pname, :qty_val, :reason, :edate, :notes,
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
            ':qty_val' => $data->quantity ?? 1,
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

            // Sincronizar referencia en la venta si el producto es diferente
            if (isset($data->productType) && $data->productType === 'different' && $saleIdInt) {
                syncWarrantyWithSaleItems($conn, $saleIdInt, $data->originalProductId, $data->newProductRef, $data->quantity ?? 1, $data->newProductName ?? null);
            }
        } else {
            logAction($conn, $_SESSION['username'], 'WARRANTY_CREATED', 'WARRANTY', $warrantyId, "Garantía registrada para Factura: " . ($data->originalSaleId ?? 'N/A'));
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

            // Sincronizar referencia en la venta si el producto es diferente
            if (($currentWarranty['product_type'] ?? null) === 'different' && ($currentWarranty['sale_id'] ?? null)) {
                syncWarrantyWithSaleItems(
                    $conn,
                    $currentWarranty['sale_id'],
                    $currentWarranty['product_ref'],
                    $currentWarranty['new_product_ref'],
                    $currentWarranty['quantity'],
                    $data->newProductName ?? $currentWarranty['new_product_name']
                );
            }
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
                
                logAction($conn, $_SESSION['username'], 'WARRANTY_INCREMENT_REMOVED', 'SALE', $saleIdInt, "Incremento por garantía eliminado: -" . formatMoney($additionalValue) . ". Nuevo total: " . formatMoney($newTotal));
            }
        }
        
        // 3. Eliminar la garantía
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
