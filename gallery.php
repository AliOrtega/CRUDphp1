<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Consulta para obtener todas las imágenes
try {
    $stmt = $conn->prepare("SELECT * FROM `images`");
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Imágenes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Mi galería de imágenes</h1>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="submit" name="submit" value="Subir Imagen">
        <a href="home.php" class="btn">Regresar al Perfil</a>
    </form>

    <div class="gallery">
        <?php
        if (count($images) > 0) {
            foreach ($images as $row) {
                echo '<div class="image">';
                
                // Determinar el tipo MIME de la imagen
                $image_data = $row['image'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($image_data);

                // Mostrar la imagen
                if (!empty($image_data)) {
                    echo '<img src="data:' . htmlspecialchars($mime_type) . ';base64,' . base64_encode($image_data) . '" alt="Imagen">';
                } else {
                    echo '<img src="images/default-avatar.png" alt="Imagen predeterminada">';
                }

                echo '<p>' . htmlspecialchars($row['image_name']) . '</p>';
                echo '<a href="edit.php?id=' . htmlspecialchars($row['id']) . '">Editar</a> | <a href="delete.php?id=' . htmlspecialchars($row['id']) . '">Borrar</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No hay imágenes subidas.</p>';
        }
        ?>
    </div>
</body>
</html>
