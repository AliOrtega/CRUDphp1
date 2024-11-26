<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;

    if (move_uploaded_file($image_temp, $image_folder)) {
        $query = "INSERT INTO `images` (image) VALUES ('$image')";
        mysqli_query($conn, $query) or die('query failed');
        header('location:index.php');
    } else {
        echo 'Error al subir la imagen.';
    }
}
?>
