<?php
// test_timezone.php - Script para verificar la configuración de zona horaria
session_start();
header('Content-Type: text/html; charset=utf-8');
require_once 'config/db.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    echo '<h1>Debes iniciar sesión primero</h1>';
    echo '<a href="login.php">Ir a Login</a>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Zona Horaria</title>
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
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 15px 0;
        }
        .success-box {
            background: #e8f5e9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 15px 0;
        }
        .warning-box {
            background: #fff3e0;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🕐 Verificación de Zona Horaria y Fechas</h1>
        
        <?php
        try {
            // 1. Verificar configuración de PHP
            echo '<div class="info-box">';
            echo '<h2>1. Configuración de PHP</h2>';
            echo '<p><strong>Zona horaria PHP:</strong> ' . date_default_timezone_get() . '</p>';
            echo '<p><strong>Fecha/Hora actual PHP:</strong> ' . date('Y-m-d H:i:s') . '</p>';
            echo '<p><strong>Formato legible:</strong> ' . date('l, d \d\e F \d\e Y - h:i:s A') . '</p>';
            echo '</div>';
            
            // 2. Verificar configuración de MySQL
            echo '<div class="info-box">';
            echo '<h2>2. Configuración de MySQL</h2>';
            $stmt = $conn->query("SELECT NOW() as mysql_time, @@session.time_zone as session_tz, @@global.time_zone as global_tz");
            $result = $stmt->fetch();
            echo '<p><strong>Fecha/Hora MySQL:</strong> ' . $result['mysql_time'] . '</p>';
            echo '<p><strong>Zona horaria de sesión:</strong> ' . $result['session_tz'] . '</p>';
            echo '<p><strong>Zona horaria global:</strong> ' . $result['global_tz'] . '</p>';
            echo '</div>';
            
            // 3. Verificar últimos registros de cada tabla
            echo '<div class="success-box">';
            echo '<h2>3. Últimos Registros en las Tablas</h2>';
            
            // Sales
            $stmt = $conn->query("SELECT id, invoice_number, sale_date FROM sales ORDER BY id DESC LIMIT 1");
            $lastSale = $stmt->fetch();
            if ($lastSale) {
                echo '<p><strong>Última Venta:</strong> #' . ($lastSale['invoice_number'] ?? $lastSale['id']) . ' - ' . $lastSale['sale_date'] . '</p>';
            } else {
                echo '<p><strong>Última Venta:</strong> No hay ventas registradas</p>';
            }
            
            // Expenses
            $stmt = $conn->query("SELECT id, description, expense_date FROM expenses ORDER BY id DESC LIMIT 1");
            $lastExpense = $stmt->fetch();
            if ($lastExpense) {
                echo '<p><strong>Último Gasto:</strong> ' . $lastExpense['description'] . ' - ' . $lastExpense['expense_date'] . '</p>';
            } else {
                echo '<p><strong>Último Gasto:</strong> No hay gastos registrados</p>';
            }
            
            // Warranties
            $stmt = $conn->query("SELECT id, customer_name, created_at FROM warranties ORDER BY id DESC LIMIT 1");
            $lastWarranty = $stmt->fetch();
            if ($lastWarranty) {
                echo '<p><strong>Última Garantía:</strong> ' . $lastWarranty['customer_name'] . ' - ' . $lastWarranty['created_at'] . '</p>';
            } else {
                echo '<p><strong>Última Garantía:</strong> No hay garantías registradas</p>';
            }
            
            // Restocks
            $stmt = $conn->query("SELECT id, product_name, restock_date FROM restocks ORDER BY id DESC LIMIT 1");
            $lastRestock = $stmt->fetch();
            if ($lastRestock) {
                echo '<p><strong>Último Surtido:</strong> ' . $lastRestock['product_name'] . ' - ' . $lastRestock['restock_date'] . '</p>';
            } else {
                echo '<p><strong>Último Surtido:</strong> No hay surtidos registrados</p>';
            }
            echo '</div>';
            
            // 4. Comparación
            echo '<div class="warning-box">';
            echo '<h2>4. Análisis</h2>';
            $phpTime = new DateTime(date('Y-m-d H:i:s'));
            $mysqlTime = new DateTime($result['mysql_time']);
            $diff = $phpTime->diff($mysqlTime);
            
            if ($diff->h == 0 && $diff->i < 2) {
                echo '<p style="color: #4CAF50;">✅ <strong>PHP y MySQL están sincronizados correctamente</strong></p>';
            } else {
                echo '<p style="color: #ff9800;">⚠️ <strong>Hay una diferencia de tiempo entre PHP y MySQL</strong></p>';
                echo '<p>Diferencia: ' . $diff->format('%h horas, %i minutos') . '</p>';
            }
            
            if (date_default_timezone_get() === 'America/Bogota') {
                echo '<p style="color: #4CAF50;">✅ <strong>Zona horaria de Colombia configurada correctamente</strong></p>';
            } else {
                echo '<p style="color: #ff9800;">⚠️ <strong>La zona horaria no está configurada para Colombia</strong></p>';
            }
            echo '</div>';
            
            // 5. Estructura de tablas
            echo '<div class="info-box">';
            echo '<h2>5. Estructura de Columnas de Fecha</h2>';
            echo '<table>';
            echo '<tr><th>Tabla</th><th>Columna de Fecha</th><th>Posición</th></tr>';
            
            $tables = ['sales' => 'sale_date', 'expenses' => 'expense_date', 'warranties' => 'created_at', 'restocks' => 'restock_date'];
            foreach ($tables as $table => $dateCol) {
                $stmt = $conn->query("DESCRIBE $table");
                $columns = $stmt->fetchAll();
                $position = 1;
                foreach ($columns as $col) {
                    if ($col['Field'] === $dateCol) {
                        $color = ($position <= 2) ? '#4CAF50' : '#ff9800';
                        echo "<tr><td>$table</td><td>$dateCol</td><td style='color: $color; font-weight: bold;'>Posición $position</td></tr>";
                        break;
                    }
                    $position++;
                }
            }
            echo '</table>';
            echo '<p><em>Nota: La posición ideal es 2 (después del ID)</em></p>';
            echo '</div>';
            
        } catch (PDOException $e) {
            echo '<div class="warning-box">';
            echo '<h2>❌ Error</h2>';
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="migration_fecha_primero.sql" class="btn">Ejecutar Migración de Columnas</a>
            <a href="index.php" class="btn" style="background: #2196F3;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html>
