<?php
// api/products.php
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
    // Listar productos
    try {
        $stmt = $conn->query("SELECT *, reference as id, purchase_price as purchasePrice, wholesale_price as wholesalePrice, retail_price as retailPrice, product_date as productDate FROM products ORDER BY created_at DESC");
        $products = $stmt->fetchAll();
        echo json_encode($products);
    } catch (PDOException $e) {
        // Auto-fix para GET
        if (strpos($e->getMessage(), "Unknown column 'product_date'") !== false) {
             try {
                $conn->exec("ALTER TABLE products ADD COLUMN product_date DATE");
                // Reintentar consulta
                $stmt = $conn->query("SELECT *, reference as id, purchase_price as purchasePrice, wholesale_price as wholesalePrice, retail_price as retailPrice, product_date as productDate FROM products ORDER BY created_at DESC");
                $products = $stmt->fetchAll();
                echo json_encode($products);
                exit;
             } catch (Exception $ex) {
                 // Fallback
             }
        }
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Agregar o Actualizar producto (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));
    
    // Validación básica
    if (!isset($data->id) || !isset($data->name)) { // JS envía 'id' como referencia
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    try {
        // Verificar si existe para UPDATE o INSERT
        // Para simplificar, asumimos INSERT o UPDATE ON DUPLICATE
        $sql = "INSERT INTO products (reference, name, quantity, purchase_price, wholesale_price, retail_price, supplier, product_date, added_by) 
                VALUES (:ref, :name, :qty, :pp, :wp, :rp, :sup, :pdate, :user)
                ON DUPLICATE KEY UPDATE 
                name = :name, quantity = :qty, purchase_price = :pp, wholesale_price = :wp, retail_price = :rp, supplier = :sup, product_date = :pdate";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':ref' => $data->id,
            ':name' => $data->name,
            ':qty' => $data->quantity,
            ':pp' => $data->purchasePrice,
            ':wp' => $data->wholesalePrice,
            ':rp' => $data->retailPrice,
            ':sup' => $data->supplier,
            ':pdate' => $data->productDate ?? null,
            ':user' => $_SESSION['username']
        ]);

        echo json_encode(['success' => true, 'message' => 'Producto guardado correctamente']);

    } catch (PDOException $e) {
        // Auto-fix: Si falta la columna product_date, la creamos y reintentamos
        if (strpos($e->getMessage(), "Unknown column 'product_date'") !== false) {
            try {
                $conn->exec("ALTER TABLE products ADD COLUMN product_date DATE");
                $stmt->execute([
                    ':ref' => $data->id,
                    ':name' => $data->name,
                    ':qty' => $data->quantity,
                    ':pp' => $data->purchasePrice,
                    ':wp' => $data->wholesalePrice,
                    ':rp' => $data->retailPrice,
                    ':sup' => $data->supplier,
                    ':pdate' => $data->productDate ?? null,
                    ':user' => $_SESSION['username']
                ]);
                echo json_encode(['success' => true, 'message' => 'Producto guardado y base de datos actualizada']);
                exit;
            } catch (Exception $ex) {
                // Si falla el fix, mostramos el error original
            }
        }

        http_response_code(500);
        echo json_encode(['error' => 'Error BD: ' . $e->getMessage()]);
    }

} elseif ($method === 'DELETE') {
    // Eliminar producto
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
        $stmt = $conn->prepare("DELETE FROM products WHERE reference = :ref");
        $stmt->execute([':ref' => $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
