<?php
include 'config.php';
session_start();

$id = $_GET['id'];

$query = "SELECT * FROM `images` WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$image_path = 'uploaded_img/' . $row['image'];

if (unlink($image_path)) {
    $query = "DELETE FROM `images` WHERE id = $id";
    mysqli_query($conn, $query) or die('query failed');
    header('location:index.php');
} else {
    echo 'Error al borrar la imagen.';
}
?>
