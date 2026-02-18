<?php
// api/sales_debug.php
// Archivo temporal para diagnosticar problemas con ventas
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/db.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Capturar datos de entrada
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput);
    
    // Log de diagnóstico
    $diagnostics = [
        'raw_input' => $rawInput,
        'decoded_data' => $data,
        'session_user_id' => $_SESSION['user_id'],
        'session_username' => $_SESSION['username'] ?? 'N/A',
        'checks' => []
    ];
    
    // Verificar estructura de datos
    if (!$data) {
        $diagnostics['checks']['json_decode'] = 'FAILED - Invalid JSON';
        echo json_encode(['error' => 'JSON inválido', 'diagnostics' => $diagnostics]);
        exit;
    }
    $diagnostics['checks']['json_decode'] = 'OK';
    
    if (!isset($data->products) || !is_array($data->products)) {
        $diagnostics['checks']['products_array'] = 'FAILED - products is not an array or missing';
        echo json_encode(['error' => 'Productos inválidos', 'diagnostics' => $diagnostics]);
        exit;
    }
    $diagnostics['checks']['products_array'] = 'OK - ' . count($data->products) . ' products';
    
    // Verificar campos requeridos
    $requiredFields = ['id', 'customerInfo', 'paymentMethod', 'deliveryType', 'total'];
    foreach ($requiredFields as $field) {
        if (!isset($data->$field)) {
            $diagnostics['checks'][$field] = 'MISSING';
        } else {
            $diagnostics['checks'][$field] = 'OK';
        }
    }
    
    // Verificar customerInfo
    if (isset($data->customerInfo)) {
        $customerFields = ['name', 'id', 'phone', 'address', 'city'];
        foreach ($customerFields as $field) {
            if (!isset($data->customerInfo->$field)) {
                $diagnostics['checks']['customerInfo_' . $field] = 'MISSING';
            } else {
                $diagnostics['checks']['customerInfo_' . $field] = 'OK';
            }
        }
    }
    
    // Verificar productos en la base de datos
    if (isset($data->products) && is_array($data->products)) {
        foreach ($data->products as $index => $item) {
            $productCheck = [
                'index' => $index,
                'productId' => $item->productId ?? 'MISSING',
                'quantity' => $item->quantity ?? 'MISSING',
                'unitPrice' => $item->unitPrice ?? 'MISSING'
            ];
            
            if (isset($item->productId)) {
                try {
                    $stmt = $conn->prepare("SELECT reference, name, quantity, purchase_price FROM products WHERE reference = :ref");
                    $stmt->execute([':ref' => $item->productId]);
                    $product = $stmt->fetch();
                    
                    if ($product) {
                        $productCheck['db_found'] = 'YES';
                        $productCheck['db_name'] = $product['name'];
                        $productCheck['db_stock'] = $product['quantity'];
                        $productCheck['requested_qty'] = $item->quantity ?? 0;
                        
                        if ($product['quantity'] < ($item->quantity ?? 0)) {
                            $productCheck['stock_status'] = 'INSUFFICIENT - Need ' . ($item->quantity ?? 0) . ', have ' . $product['quantity'];
                        } else {
                            $productCheck['stock_status'] = 'OK';
                        }
                    } else {
                        $productCheck['db_found'] = 'NO - Product reference not found in database';
                    }
                } catch (PDOException $e) {
                    $productCheck['db_error'] = $e->getMessage();
                }
            }
            
            $diagnostics['products'][] = $productCheck;
        }
    }
    
    // Verificar si el ID de factura ya existe
    if (isset($data->id)) {
        try {
            $checkStmt = $conn->prepare("SELECT COUNT(*) FROM sales WHERE invoice_number = :inv");
            $checkStmt->execute([':inv' => $data->id]);
            $count = $checkStmt->fetchColumn();
            
            if ($count > 0) {
                $diagnostics['checks']['invoice_duplicate'] = 'YES - Invoice number already exists';
            } else {
                $diagnostics['checks']['invoice_duplicate'] = 'NO - Invoice number is unique';
            }
        } catch (PDOException $e) {
            $diagnostics['checks']['invoice_check_error'] = $e->getMessage();
        }
    }
    
    // Verificar conexión a la base de datos
    try {
        $conn->query("SELECT 1");
        $diagnostics['checks']['database_connection'] = 'OK';
    } catch (PDOException $e) {
        $diagnostics['checks']['database_connection'] = 'FAILED - ' . $e->getMessage();
    }
    
    echo json_encode([
        'message' => 'Diagnóstico completado',
        'diagnostics' => $diagnostics
    ], JSON_PRETTY_PRINT);
    
} else {
    echo json_encode(['error' => 'Método no soportado. Use POST']);
}
?>
