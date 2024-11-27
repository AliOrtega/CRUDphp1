<?php
$servername = getenv('MYSQLHOST');
$username = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$dbname = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;port=$port";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
