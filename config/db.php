<?php
// config/db.php

$host = 'sql308.infinityfree.com';
$db_name = 'if0_41128039_destellodeoro18kv2';
$username = 'if0_41128039';
$password = 'Jd1Id4AGBPyam3'; // Por defecto en XAMPP es vacío

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Configurar zona horaria de Colombia (UTC-5)
    date_default_timezone_set('America/Bogota');
    // Configurar zona horaria en MySQL
    $conn->exec("SET time_zone = '-05:00'");
} catch(PDOException $e) {
    // En producción, no mostrar el error detallado
    die("Error de conexión: " . $e->getMessage());
}
?>
