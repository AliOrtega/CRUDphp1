<?php
include 'config.php';
session_start();

$id = $_GET['id'];

if (isset($_POST['update'])) {
    $new_name = $_POST['new_name'];
    $query = "SELECT * FROM `images` WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $old_name = $row['image'];
    $old_path = 'uploaded_img/' . $old_name;
    $new_path = 'uploaded_img/' . $new_name;

    if (rename($old_path, $new_path)) {
        $query = "UPDATE `images` SET image = '$new_name' WHERE id = $id";
        mysqli_query($conn, $query) or die('query failed');
        header('location:index.php');
    } else {
        echo 'Error al renombrar la imagen.';
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
</head>
<body>
    <form action="" method="post">
        <input type="text" name="new_name" placeholder="Nuevo nombre de la imagen" required>
        <input type="submit" name="update" value="Actualizar Imagen">
    </form>
</body>
</html>
