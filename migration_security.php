<?php
require_once 'config/db.php';

try {
    $conn->exec("ALTER TABLE users 
                ADD COLUMN failed_attempts INT DEFAULT 0,
                ADD COLUMN locked_until DATETIME DEFAULT NULL,
                ADD COLUMN security_token VARCHAR(255) DEFAULT NULL;") ;
    echo "Columns added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Columns already exist.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
