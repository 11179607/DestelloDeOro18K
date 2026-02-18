<?php
// test_sale.php - Script para probar el endpoint de ventas y ver el error exacto
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== PRUEBA DE VENTA ===\n\n";

// Simular una sesión de usuario
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['role'] = 'admin';

echo "Sesión iniciada como: " . $_SESSION['username'] . "\n\n";

// Datos de prueba para una venta
$saleData = [
    'id' => 'TEST-' . time(),
    'customerInfo' => [
        'name' => 'Cliente de Prueba',
        'id' => '1234567890',
        'phone' => '3001234567',
        'email' => 'test@test.com',
        'address' => 'Calle 123',
        'city' => 'Bogotá'
    ],
    'products' => [
        [
            'productId' => 'PROD001',
            'productName' => 'Producto de Prueba',
            'quantity' => 1,
            'unitPrice' => 100000,
            'subtotal' => 100000,
            'saleType' => 'retail'
        ]
    ],
    'paymentMethod' => 'cash',
    'deliveryType' => 'pickup',
    'total' => 100000,
    'discount' => 0,
    'deliveryCost' => 0,
    'warrantyIncrement' => 0,
    'date' => date('Y-m-d')
];

echo "Datos de venta:\n";
echo json_encode($saleData, JSON_PRETTY_PRINT) . "\n\n";

// Simular la solicitud POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$jsonData = json_encode($saleData);

// Crear un archivo temporal con los datos
$tempFile = tempnam(sys_get_temp_dir(), 'sale_test_');
file_put_contents($tempFile, $jsonData);

// Redirigir php://input al archivo temporal
echo "Ejecutando api/sales.php...\n\n";
echo "=== INICIO DE RESPUESTA ===\n";

// Capturar la salida
ob_start();

// Simular php://input
$GLOBALS['_test_input'] = $jsonData;

// Modificar temporalmente file_get_contents para usar nuestros datos de prueba
function custom_file_get_contents($filename) {
    if ($filename === 'php://input') {
        return $GLOBALS['_test_input'];
    }
    return file_get_contents($filename);
}

// Incluir el archivo de ventas
try {
    // Cambiar al directorio de la API
    chdir(__DIR__ . '/api');
    
    // Ejecutar el script
    include 'sales.php';
    
} catch (Exception $e) {
    echo "EXCEPCIÓN CAPTURADA: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

$output = ob_get_clean();

echo $output . "\n";
echo "=== FIN DE RESPUESTA ===\n\n";

// Limpiar
unlink($tempFile);

// Intentar decodificar la respuesta JSON
echo "Análisis de respuesta:\n";
$response = json_decode($output, true);
if ($response) {
    echo "Respuesta JSON válida:\n";
    print_r($response);
    
    if (isset($response['error'])) {
        echo "\n❌ ERROR DETECTADO: " . $response['error'] . "\n";
        if (isset($response['details'])) {
            echo "Detalles: " . $response['details'] . "\n";
        }
        if (isset($response['help'])) {
            echo "Ayuda: " . $response['help'] . "\n";
        }
    } elseif (isset($response['success'])) {
        echo "\n✅ VENTA EXITOSA\n";
    }
} else {
    echo "No se pudo decodificar como JSON. Respuesta cruda:\n";
    echo $output . "\n";
}

echo "\n=== FIN DE PRUEBA ===\n";
?>
