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
    $images = $stmt->fetchAll();
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
                echo '<img src="uploaded_img/'.$row['image'].'" alt="Imagen">';
                echo '<p>'.$row['image'].'</p>';
                echo '<a href="edit.php?id='.$row['id'].'">Editar</a> | <a href="delete.php?id='.$row['id'].'">Borrar</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No hay imágenes subidas.</p>';
        }
        ?>
    </div>
</body>
</html>
