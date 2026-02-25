<?php
// api/expenses.php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once '../config/db.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    try {
        $month = $_GET['month'] ?? null;
        $year = $_GET['year'] ?? null;
        
        $sql = "SELECT * FROM expenses";
        $params = [];
        
        if ($month !== null && $year !== null) {
            $month = intval($month); // JS envía 0-11, o -1 para todos
            if ($month === -1) {
                $sql .= " WHERE YEAR(expense_date) = :year";
                $params[':year'] = $year;
            } else {
                $month = $month + 1; // 0-11 -> 1-12
                $sql .= " WHERE MONTH(expense_date) = :month AND YEAR(expense_date) = :year";
                $params[':month'] = $month;
                $params[':year'] = $year;
            }
        }
        
        $sql .= " ORDER BY expense_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $expenses = $stmt->fetchAll();
        // Mapear a formato JS (expense_date -> date)
        $mapped = array_map(function($e) {
            return [
                'id' => $e['id'],
                'description' => $e['description'],
                'amount' => $e['amount'],
                'date' => $e['expense_date'],
                'user' => $e['username'] // JS espera 'admin' o 'worker' (username)
            ];
        }, $expenses);
        
        echo json_encode($mapped);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($method === 'POST') {
    // Agregar Gasto
    $data = json_decode(file_get_contents("php://input"));
    
    if (!$data || !isset($data->description) || !isset($data->amount)) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    try {
        $incomingDate = $data->date ?? $data->expenseDate ?? null;
        $expenseDate = $incomingDate ? ((strlen($incomingDate) === 10) ? ($incomingDate . ' ' . date('H:i:s')) : $incomingDate) : date('Y-m-d H:i:s');

        $stmt = $conn->prepare("INSERT INTO expenses (description, amount, expense_date, user_id, username) VALUES (:desc, :amt, :date, :uid, :uname)");
        
        $stmt->execute([
            ':desc' => $data->description,
            ':amt' => $data->amount,
            ':date' => $expenseDate,
            ':uid' => $_SESSION['user_id'],
            ':uname' => $_SESSION['username']
        ]);

        echo json_encode(['success' => true, 'message' => 'Gasto registrado']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar Gasto (Solo admin)
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Acceso denegado']);
        exit;
    }
    
    $id = $_GET['id'] ?? null;
    
    try {
        $stmt = $conn->prepare("DELETE FROM expenses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($method === 'PUT') {
    // Editar Gasto (Solo admin)
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
        $incomingDate = $data->date ?? $data->expenseDate ?? null;
        $expenseDate = $incomingDate ? ((strlen($incomingDate) === 10) ? ($incomingDate . ' ' . date('H:i:s')) : $incomingDate) : date('Y-m-d H:i:s');

        $stmt = $conn->prepare("UPDATE expenses SET description = :desc, amount = :amt, expense_date = :date WHERE id = :id");
        $stmt->execute([
            ':desc' => $data->description,
            ':amt' => $data->amount,
            ':date' => $expenseDate,
            ':id' => $data->id
        ]);
        echo json_encode(['success' => true, 'message' => 'Gasto actualizado']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
