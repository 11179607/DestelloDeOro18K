<?php
// api/logger.php
require_once '../config/db.php';

function ensureLogsTableExists($conn) {
    try {
        $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_username VARCHAR(50) NOT NULL,
            action_type VARCHAR(50) NOT NULL, -- DELETE, EDIT, CONFIRM_PAYMENT
            entity_type VARCHAR(50) NOT NULL, -- SALE, EXPENSE, RESTOCK, WARRANTY, USER
            entity_id VARCHAR(50),
            details TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) CHARACTER SET utf8 COLLATE utf8_general_ci";
        
        $conn->exec($sql);
    } catch (PDOException $e) {
        // En caso de error, podemos registrarlo en un archivo de log de servidor si es necesario
        error_log("Error creating audit_logs table: " . $e->getMessage());
    }
}

function logAction($conn, $user, $action, $entityType, $entityId, $details) {
    ensureLogsTableExists($conn);
    
    try {
        $stmt = $conn->prepare("INSERT INTO audit_logs (user_username, action_type, entity_type, entity_id, details) VALUES (:user, :action, :entityType, :entityId, :details)");
        $stmt->execute([
            ':user' => $user,
            ':action' => $action,
            ':entityType' => $entityType,
            ':entityId' => $entityId,
            ':details' => $details
        ]);
    } catch (PDOException $e) {
        error_log("Error logging action: " . $e->getMessage());
    }
}
?>
