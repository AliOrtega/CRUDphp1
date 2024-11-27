<?php
$servername = "junction.proxy.rlwy.net";
$username = "root";
$password = "UAXATIKXNutukAOSqYCxSYGQfxwOLtAR";
$dbname = "railway";
$port = 13081;

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;port=$port";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    $conn = new PDO($dsn, $username, $password, $options);
    echo "Conectado exitosamente";
} catch (PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
