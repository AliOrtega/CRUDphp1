<?php
$servername = getenv('MYSQLHOST') ?: 'localhost';
$username = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$dbname = getenv('MYSQLDATABASE') ?: 'test';
$port = getenv('MYSQLPORT') ?: 3306;

try {
    $dsn = "mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $conn = new PDO($dsn, $username, $password, $options);
    // Establecer el atributo para manejar grandes tamaños de buffer, si es necesario
    $conn->setAttribute(PDO::MYSQL_ATTR_MAX_BUFFER_SIZE, 1024 * 1024 * 10); // 10MB buffer
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
