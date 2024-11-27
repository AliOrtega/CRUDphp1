<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/'.$image;

    // Crear el directorio si no existe
    if (!is_dir('uploaded_img')) {
        mkdir('uploaded_img', 0755, true);
    }

    if (move_uploaded_file($image_temp, $image_folder)) {
        try {
            $stmt = $conn->prepare("INSERT INTO `images` (image) VALUES (:image)");
            $stmt->execute(['image' => $image]);
            header('Location: gallery.php');
            exit();
        } catch (PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    } else {
        echo 'Error al subir la imagen.';
    }
}
?>
