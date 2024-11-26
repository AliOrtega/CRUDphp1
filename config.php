<?php

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$db_name = getenv('DB_NAME');
$port = getenv('DB_PORT');

$conn = mysqli_connect($host, $user, $password, $db_name, $port) or die('connection failed: ' . mysqli_connect_error());

// Verificar conexión
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

echo 'Connected successfully';

?>
