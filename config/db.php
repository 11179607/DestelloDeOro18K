<?php
// config/db.php

// Cargar variables locales si existen (no versionadas)
@include __DIR__ . '/env.php';

$host = getenv('DB_HOST') ?: ($ENV_DB_HOST ?? 'sql308.infinityfree.com');
$db_name = getenv('DB_NAME') ?: ($ENV_DB_NAME ?? 'destello_db');
$username = getenv('DB_USER') ?: ($ENV_DB_USER ?? 'root');
$password = getenv('DB_PASSWORD') ?: ($ENV_DB_PASSWORD ?? '');

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
