<?php
include 'config.php';
session_start();

$id = $_GET['id'];

try {
    // Usar PDO para la consulta SELECT
    $stmt = $conn->prepare("SELECT * FROM `images` WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Si las imágenes se guardan como datos binarios en la base de datos
        $image_data = $row['image'];
        
        // Si las imágenes se guardan en un directorio, usa esta línea
        $image_path = 'uploaded_img/' . $row['image'];

        // Verificar y borrar la imagen del directorio
        if (!empty($image_path) && file_exists($image_path) && unlink($image_path)) {
            // Usar PDO para la consulta DELETE
            $stmt = $conn->prepare("DELETE FROM `images` WHERE id = :id");
            $stmt->execute(['id' => $id]);
            header('Location: gallery.php');
            exit();
        } elseif (!empty($image_data)) {
            // Borrar la imagen de la base de datos
            $stmt = $conn->prepare("DELETE FROM `images` WHERE id = :id");
            $stmt->execute(['id' => $id]);
            header('Location: gallery.php');
            exit();
        } else {
            echo 'Error al borrar la imagen.';
        }
    } else {
        echo 'Imagen no encontrada.';
    }
} catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
}
?>
