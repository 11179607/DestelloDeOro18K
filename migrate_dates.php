<?php
// Script de migración para reorganizar columnas de fecha
require_once 'config/db.php';

echo "Iniciando migración de columnas de fecha...\n\n";

try {
    // 1. TABLA SALES: Reorganizar para que sale_date sea la primera columna después del ID
    echo "1. Reorganizando tabla SALES...\n";
    $conn->exec("ALTER TABLE sales MODIFY COLUMN sale_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo "   ✓ Columna sale_date movida después de id\n\n";

    // 2. TABLA EXPENSES: Reorganizar para que expense_date sea la primera columna después del ID
    echo "2. Reorganizando tabla EXPENSES...\n";
    $conn->exec("ALTER TABLE expenses MODIFY COLUMN expense_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo "   ✓ Columna expense_date movida después de id\n\n";

    // 3. TABLA WARRANTIES: Reorganizar para que created_at sea la primera columna después del ID
    echo "3. Reorganizando tabla WARRANTIES...\n";
    $conn->exec("ALTER TABLE warranties MODIFY COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo "   ✓ Columna created_at movida después de id\n\n";

    // 4. TABLA RESTOCKS: Reorganizar para que restock_date sea la primera columna después del ID
    echo "4. Reorganizando tabla RESTOCKS...\n";
    $conn->exec("ALTER TABLE restocks MODIFY COLUMN restock_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo "   ✓ Columna restock_date movida después de id\n\n";

    // 5. TABLA PRODUCTS: Reorganizar para que created_at sea la primera columna después de reference
    echo "5. Reorganizando tabla PRODUCTS...\n";
    $conn->exec("ALTER TABLE products MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER reference");
    echo "   ✓ Columna created_at movida después de reference\n\n";

    echo "===========================================\n";
    echo "✓ MIGRACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "===========================================\n\n";

    // Mostrar estructura de las tablas
    echo "Verificando estructura de las tablas:\n\n";
    
    $tables = ['sales', 'expenses', 'warranties', 'restocks', 'products'];
    foreach ($tables as $table) {
        echo "Tabla: $table\n";
        $stmt = $conn->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo "  - {$col['Field']} ({$col['Type']})\n";
        }
        echo "\n";
    }

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
