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
        $stmt = $conn->query("SELECT *, reference as id, entry_date as date, purchase_price as purchasePrice, wholesale_price as wholesalePrice, retail_price as retailPrice FROM products ORDER BY created_at DESC");
        $products = $stmt->fetchAll();
        echo json_encode($products);
    } catch (PDOException $e) {
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
    
    // Validaci?n b?sica
    if (!isset($data->id) || !isset($data->name)) { // JS env?a 'id' como referencia
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    $originalId = (isset($data->originalId) && $data->originalId) ? $data->originalId : $data->id;

    try {
        // Asegurar que la columna existe (opcional, para mayor robustez en esta actualizaci?n)
        $conn->exec("ALTER TABLE products ADD COLUMN IF NOT EXISTS entry_date DATE AFTER reference");
        
        // Si se cambia la referencia, validar que la nueva no exista y actualizar PK
        if ($originalId !== $data->id) {
            $check = $conn->prepare("SELECT reference FROM products WHERE reference = :ref");
            $check->execute([':ref' => $data->id]);
            if ($check->fetch()) {
                http_response_code(409);
                echo json_encode(['error' => 'La nueva referencia ya existe.']);
                exit;
            }

            $update = $conn->prepare("UPDATE products SET reference = :ref, entry_date = :entry_date, name = :name, quantity = :qty, purchase_price = :pp, wholesale_price = :wp, retail_price = :rp, supplier = :sup, updated_at = NOW() WHERE reference = :original");
            $update->execute([
                ':ref' => $data->id,
                ':entry_date' => $data->date,
                ':name' => $data->name,
                ':qty' => $data->quantity,
                ':pp' => $data->purchasePrice,
                ':wp' => $data->wholesalePrice,
                ':rp' => $data->retailPrice,
                ':sup' => $data->supplier,
                ':original' => $originalId
            ]);

            // Si no exist?a el registro anterior, crear uno nuevo
            if ($update->rowCount() === 0) {
                $insert = $conn->prepare("INSERT INTO products (reference, entry_date, name, quantity, purchase_price, wholesale_price, retail_price, supplier, added_by) 
                        VALUES (:ref, :entry_date, :name, :qty, :pp, :wp, :rp, :sup, :user)");
                $insert->execute([
                    ':ref' => $data->id,
                    ':entry_date' => $data->date,
                    ':name' => $data->name,
                    ':qty' => $data->quantity,
                    ':pp' => $data->purchasePrice,
                    ':wp' => $data->wholesalePrice,
                    ':rp' => $data->retailPrice,
                    ':sup' => $data->supplier,
                    ':user' => $_SESSION['username']
                ]);
            }
        } else {
            // Actualizar/insertar cuando la referencia no cambia
            $sql = "INSERT INTO products (reference, entry_date, name, quantity, purchase_price, wholesale_price, retail_price, supplier, added_by) 
                    VALUES (:ref, :entry_date, :name, :qty, :pp, :wp, :rp, :sup, :user)
                    ON DUPLICATE KEY UPDATE 
                    entry_date = :entry_date, name = :name, quantity = :qty, purchase_price = :pp, wholesale_price = :wp, retail_price = :rp, supplier = :sup";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':ref' => $data->id,
                ':entry_date' => $data->date,
                ':name' => $data->name,
                ':qty' => $data->quantity,
                ':pp' => $data->purchasePrice,
                ':wp' => $data->wholesalePrice,
                ':rp' => $data->retailPrice,
                ':sup' => $data->supplier,
                ':user' => $_SESSION['username']
            ]);
        }

        echo json_encode(['success' => true, 'message' => 'Producto guardado correctamente']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
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
