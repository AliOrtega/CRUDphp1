<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:login.php');
    exit();
}

try {
    $select = $conn->prepare("SELECT * FROM `user_form` WHERE id = ?");
    $select->execute([$user_id]);
    $fetch = $select->fetch(PDO::FETCH_ASSOC);

    if (!$fetch) {
        echo 'Usuario no encontrado';
    }

    // Determinar el tipo MIME de la imagen
    $image_data = $fetch['image'];
    $mime_type = false;
    if (!empty($image_data)) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($image_data);
    }
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
    <title>home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<div class="container">
    <div class="profile">
        <?php
        if ($mime_type && !empty($image_data)): ?>
            <img src="data:<?php echo htmlspecialchars($mime_type); ?>;base64,<?php echo base64_encode($image_data); ?>" alt="Imagen">
        <?php else: ?>
            <img src="images/default-avatar.png" alt="Imagen predeterminada">
        <?php endif; ?>
        <h3><?php echo htmlspecialchars($fetch['name']); ?></h3>
        <a href="update_profile.php" class="btn">Actualización del perfil</a>
        <a href="gallery.php" class="btn">Mi galería de imágenes</a>
        <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn">Salir</a>
        <p>Regresar al <a href="login.php">Ingreso</a> o al <a href="register.php">Registro</a></p>
    </div>
</div>

</body>
</html>
