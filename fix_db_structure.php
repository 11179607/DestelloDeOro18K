<?php
// fix_db_structure.php
// ACCEDE A ESTE ARCHIVO DESDE EL NAVEGADOR: http://localhost/DestellodeOro18K/fix_db_structure.php

header('Content-Type: text/html; charset=utf-8');

// Intentar cargar configuración
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} elseif (file_exists('../config/db.php')) {
    require_once '../config/db.php';
} else {
    die("Error: No se encuentra config/db.php. Asegúrate de estar en la raíz del proyecto.");
}

echo "<h1>Actualización de Base de Datos</h1>";
echo "<p>Conectado a la base de datos...</p>";
echo "<ul>";

// Función auxiliar para alter table
function addColumn($conn, $table, $colName, $colDef) {
    try {
        // Verificar si existe primero (query simple)
        $stmt = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$colName'");
        if ($stmt->fetch()) {
            echo "<li style='color: green;'>Columna <b>$colName</b> en tabla <b>$table</b> ya existe. (OK)</li>";
            return;
        }

        $sql = "ALTER TABLE `$table` ADD COLUMN $colDef";
        $conn->exec($sql);
        echo "<li style='color: blue;'>Añadida columna <b>$colName</b> en tabla <b>$table</b>. (CORREGIDO)</li>";

    } catch (PDOException $e) {
        echo "<li style='color: red;'>Error al añadir <b>$colName</b>: " . $e->getMessage() . "</li>";
    }
}

// 1. Tabla PRODUCTS
// Faltan: entry_date, wholesale_price, retail_price, supplier, added_by
// Definición original en api/products.php:
// INSERT INTO products (reference, entry_date, name, quantity, purchase_price, wholesale_price, retail_price, supplier, added_by)

echo "<h3>Revisando tabla PRODUCTS...</h3>";
addColumn($conn, 'products', 'entry_date', "entry_date DATE AFTER reference");
addColumn($conn, 'products', 'wholesale_price', "wholesale_price DECIMAL(10,2) DEFAULT 0");
addColumn($conn, 'products', 'retail_price', "retail_price DECIMAL(10,2) DEFAULT 0");
addColumn($conn, 'products', 'supplier', "supplier VARCHAR(100)");
addColumn($conn, 'products', 'added_by', "added_by VARCHAR(50)");

// 2. Tabla SALES
echo "<h3>Revisando tabla SALES...</h3>";
addColumn($conn, 'sales', 'user_id', "user_id INT");
addColumn($conn, 'sales', 'username', "username VARCHAR(50)");
addColumn($conn, 'sales', 'status', "status VARCHAR(20) DEFAULT 'completed'");

echo "</ul>";
echo "<h2>Proceso completado.</h2>";
echo "<p>Ahora intenta realizar la operación de nuevo en el sistema.</p>";
echo "<a href='index.php'>Volver al inicio</a>";
?>
