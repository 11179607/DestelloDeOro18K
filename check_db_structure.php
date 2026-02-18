<?php
require_once 'config/db.php';
header('Content-Type: text/plain');

function checkTable($conn, $tableName) {
    echo "--- Table: $tableName ---\n";
    try {
        $stmt = $conn->query("DESCRIBE $tableName");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            print_r($row);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

checkTable($conn, 'sales');
checkTable($conn, 'sale_items');
checkTable($conn, 'products');
checkTable($conn, 'users');
?>
