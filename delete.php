<?php
include 'config.php';
session_start();

$id = $_GET['id'];

// Usar PDO para la consulta SELECT
try {
    $stmt = $conn->prepare("SELECT * FROM `images` WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $image_path = 'uploaded_img/' . $row['image'];

        if (unlink($image_path)) {
            // Usar PDO para la consulta DELETE
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
