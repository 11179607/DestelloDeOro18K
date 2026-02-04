<?php
// api/migrate_database.php - Script de migración accesible desde el navegador
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once '../config/db.php';

// Verificar autenticación y permisos de admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo '<h1>Acceso Denegado</h1><p>Solo administradores pueden ejecutar migraciones.</p>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migración de Base de Datos</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .step {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #2196F3;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
        .error {
            color: #f44336;
            font-weight: bold;
        }
        .info {
            color: #2196F3;
        }
        pre {
            background: #263238;
            color: #aed581;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Migración de Base de Datos - Reorganización de Columnas de Fecha</h1>
        <p class="info">Esta migración reorganizará las columnas de fecha en todas las tablas para que aparezcan primero después del ID.</p>

<?php

try {
    echo '<div class="step">';
    echo '<h2>1. Reorganizando tabla SALES</h2>';
    $conn->exec("ALTER TABLE sales MODIFY COLUMN sale_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo '<p class="success">✓ Columna sale_date movida después de id</p>';
    echo '</div>';

    echo '<div class="step">';
    echo '<h2>2. Reorganizando tabla EXPENSES</h2>';
    $conn->exec("ALTER TABLE expenses MODIFY COLUMN expense_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo '<p class="success">✓ Columna expense_date movida después de id</p>';
    echo '</div>';

    echo '<div class="step">';
    echo '<h2>3. Reorganizando tabla WARRANTIES</h2>';
    $conn->exec("ALTER TABLE warranties MODIFY COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo '<p class="success">✓ Columna created_at movida después de id</p>';
    echo '</div>';

    echo '<div class="step">';
    echo '<h2>4. Reorganizando tabla RESTOCKS</h2>';
    $conn->exec("ALTER TABLE restocks MODIFY COLUMN restock_date DATETIME DEFAULT CURRENT_TIMESTAMP AFTER id");
    echo '<p class="success">✓ Columna restock_date movida después de id</p>';
    echo '</div>';

    echo '<div class="step">';
    echo '<h2>5. Reorganizando tabla PRODUCTS</h2>';
    $conn->exec("ALTER TABLE products MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER reference");
    echo '<p class="success">✓ Columna created_at movida después de reference</p>';
    echo '</div>';

    echo '<div class="step" style="border-left-color: #4CAF50; background: #e8f5e9;">';
    echo '<h2 class="success">✓ MIGRACIÓN COMPLETADA EXITOSAMENTE</h2>';
    echo '<p>Todas las columnas de fecha han sido reorganizadas correctamente.</p>';
    echo '</div>';

    // Mostrar estructura de las tablas
    echo '<div class="step">';
    echo '<h2>Verificación de Estructura de Tablas</h2>';
    
    $tables = ['sales', 'expenses', 'warranties', 'restocks', 'products'];
    foreach ($tables as $table) {
        echo "<h3>Tabla: $table</h3>";
        echo '<table>';
        echo '<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>';
        
        $stmt = $conn->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($col['Field']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Type']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Null']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Key']) . '</td>';
            echo '<td>' . htmlspecialchars($col['Default'] ?? 'NULL') . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    echo '</div>';

} catch (PDOException $e) {
    echo '<div class="step" style="border-left-color: #f44336; background: #ffebee;">';
    echo '<h2 class="error">❌ ERROR EN LA MIGRACIÓN</h2>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    echo '</div>';
}

?>
        <div style="margin-top: 30px; text-align: center;">
            <a href="../index.php" style="display: inline-block; padding: 12px 24px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html>
