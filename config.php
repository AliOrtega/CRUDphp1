<?php

// Obtener variables de entorno
$dbHost = getenv('DB_HOST') ?: 'localhost'; 
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: 'root';
$dbName = getenv('DB_NAME') ?: 'used_db';
$dbPort = getenv('DB_PORT') ?: 3306;

// Establecer conexión
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

?>
