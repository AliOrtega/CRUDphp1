<?php
include 'config.php';
session_start();

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener la imagen actual para eliminarla si es necesario
        $stmt = $conn->prepare("SELECT * FROM `images` WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Verificar si se subió una nueva imagen
            if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name']) {
                $image_data = file_get_contents($_FILES['image']['tmp_name']);
                
                // Determinar el tipo MIME de la imagen
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->file($_FILES['image']['tmp_name']);

                // Actualizar la imagen en la base de datos
                $stmt = $conn->prepare("UPDATE `images` SET image = :image, mime_type = :mime_type WHERE id = :id");
                $stmt->bindParam(':image', $image_data, PDO::PARAM_LOB);
                $stmt->bindParam(':mime_type', $mime_type);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                // Eliminar la imagen antigua del directorio
                if (!empty($row['image'])) {
                    $old_image_path = 'uploaded_img/' . $row['image'];
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }
            }

            header('Location: gallery.php');
            exit();
        } else {
            echo 'Imagen no encontrada.';
        }
    } catch (PDOException $e) {
        die('Query failed: ' . $e->getMessage());
    }
} else {
    // Obtener la imagen actual para mostrarla en el formulario de edición
    try {
        $stmt = $conn->prepare("SELECT * FROM `images` WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Query failed: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Imagen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Editar Imagen</h1>

    <form action="edit.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype="multipart/form-data">
        <?php if ($row): ?>
            <?php
            // Mostrar la imagen actual
            $image_data = $row['image'];
            $mime_type = $row['mime_type'];
            if (!empty($image_data) && !empty($mime_type)): ?>
                <img src="data:<?php echo htmlspecialchars($mime_type); ?>;base64,<?php echo base64_encode($image_data); ?>" alt="Imagen actual">
            <?php else: ?>
                <p>No hay imagen disponible.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Imagen no encontrada.</p>
        <?php endif; ?>
        
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png">
        <input type="submit" value="Actualizar Imagen">
        <a href="gallery.php" class="btn">Regresar a la Galería</a>
    </form>
</body>
</html>
