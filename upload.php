<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $image_name = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_type = mime_content_type($image_temp);

    // Validar el tipo y tamaño de la imagen
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($image_type, $allowed_types)) {
        echo 'Tipo de archivo no válido. Solo se permiten imágenes JPEG y PNG.';
    } elseif ($image_size > 2000000) {
        echo 'El tamaño de la imagen es demasiado grande. Máximo 2MB.';
    } else {
        $image_data = file_get_contents($image_temp);

        try {
            $stmt = $conn->prepare("INSERT INTO `images` (image, image_name, mime_type) VALUES (:image, :image_name, :mime_type)");
            $stmt->bindParam(':image', $image_data, PDO::PARAM_LOB);
            $stmt->bindParam(':image_name', $image_name);
            $stmt->bindParam(':mime_type', $image_type);
            $stmt->execute();
            header('Location: gallery.php');
            exit();
        } catch (PDOException $e) {
            die('Error en la consulta: ' . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Subir Imagen</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" required>
        <input type="submit" name="submit" value="Subir Imagen">
        <a href="gallery.php" class="btn">Regresar a la Galería</a>
    </form>
</body>
</html>
