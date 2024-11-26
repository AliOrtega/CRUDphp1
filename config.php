<?php
// Cargar variables de entorno (si estás usando phpdotenv)
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener las variables de entorno
$dbHost = getenv('DB_HOST') ?: 'https://crudphp1-production.up.railway.app/';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: 'root';
$dbName = getenv('DB_NAME') ?: 'used_db';
$dbPort = getenv('DB_PORT') ?: 3306;

// Establecer conexión con la base de datos
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>
