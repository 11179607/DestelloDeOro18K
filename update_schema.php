<?php
// update_schema.php
header('Content-Type: text/html; charset=utf-8');
require_once 'config/db.php';

echo "<h2>Actualización de Base de Datos - Destello de Oro 18K</h2>";

try {
    // 1. Agregar columna warranty_increment si no existe
    $sql = "ALTER TABLE sales ADD COLUMN warranty_increment DECIMAL(15, 2) DEFAULT 0 AFTER delivery_cost";
    
    // Verificamos si ya existe antes para no dar error
    $check = $conn->query("SHOW COLUMNS FROM sales LIKE 'warranty_increment'");
    if ($check->rowCount() == 0) {
        $conn->exec($sql);
        echo "<div style='color: green; padding: 10px; border: 1px solid green;'>✅ Columna <b>warranty_increment</b> agregada correctamente a la tabla 'sales'.</div>";
    } else {
        echo "<div style='color: blue; padding: 10px; border: 1px solid blue;'>ℹ️ La columna <b>warranty_increment</b> ya existe en la base de datos.</div>";
    }

    echo "<p>Ya puedes cerrar esta ventana y volver a la aplicación. El error de edición debería haber desaparecido.</p>";
    echo "<a href='index.php' style='padding: 10px 20px; background: #d4af37; color: white; text-decoration: none; border-radius: 5px;'>Volver al Sistema</a>";

} catch (PDOException $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red;'>❌ Error actualizando la base de datos: " . $e->getMessage() . "</div>";
    echo "<p>Si el error dice 'Duplicate column name', significa que ya estaba creada.</p>";
}
?>
