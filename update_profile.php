<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $update_name = htmlspecialchars($_POST['update_name'] ?? '');
    $update_email = htmlspecialchars($_POST['update_email'] ?? '');

    try {
        // Actualizar nombre y email
        $stmt = $conn->prepare("UPDATE `user_form` SET name = :name, email = :email WHERE id = :id");
        $stmt->execute(['name' => $update_name, 'email' => $update_email, 'id' => $user_id]);

        // Manejo de contraseñas
        $old_pass = $_POST['old_pass'];
        $update_pass = md5($_POST['update_pass']);
        $new_pass = md5($_POST['new_pass']);
        $confirm_pass = md5($_POST['confirm_pass']);

        if (!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)) {
            if ($update_pass != $old_pass) {
                $message[] = '¡La contraseña anterior no coincide!';
            } elseif ($new_pass != $confirm_pass) {
                $message[] = '¡La confirmación de la contraseña no coincide!';
            } else {
                $stmt = $conn->prepare("UPDATE `user_form` SET password = :password WHERE id = :id");
                $stmt->execute(['password' => $confirm_pass, 'id' => $user_id]);
                $message[] = '¡Contraseña actualizada con éxito!';
            }
        }

        // Manejo de imagen
        if (isset($_FILES['update_image']['tmp_name']) && $_FILES['update_image']['tmp_name']) {
            $update_image_data = file_get_contents($_FILES['update_image']['tmp_name']);
            $update_image_size = $_FILES['update_image']['size'];
            $update_image_type = mime_content_type($_FILES['update_image']['tmp_name']);
            
            if ($update_image_size > 2000000) {
                $message[] = '¡La imagen es demasiado grande!';
            } else {
                $stmt = $conn->prepare("UPDATE `user_form` SET image = :image, mime_type = :mime_type WHERE id = :id");
                $stmt->bindParam(':image', $update_image_data, PDO::PARAM_LOB);
                $stmt->bindParam(':mime_type', $update_image_type);
                $stmt->bindParam(':id', $user_id);
                $stmt->execute();
                $message[] = '¡Imagen actualizada con éxito!';
            }
        }

    } catch (PDOException $e) {
        die('Error en la consulta: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Perfil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<div class="update-profile">
    <?php
        try {
            $stmt = $conn->prepare("SELECT * FROM `user_form` WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Error en la consulta: ' . $e->getMessage());
        }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php
            if (empty($fetch['image'])) {
                echo '<img src="images/default-avatar.png">';
            } else {
                $mime_type = htmlspecialchars($fetch['mime_type'] ?? 'image/jpeg');
                $image = base64_encode($fetch['image'] ?? '');
                echo '<img src="data:' . $mime_type . ';base64,' . $image . '" alt="Imagen de perfil">';
            }
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
                }
            }
        ?>
        <div class="flex">
            <div class="inputBox">
                <span>Nombre de usuario :</span>
                <input type="text" name="update_name" value="<?php echo htmlspecialchars($fetch['name'] ?? ''); ?>" class="box">
                <span>Email :</span>
                <input type="email" name="update_email" value="<?php echo htmlspecialchars($fetch['email'] ?? ''); ?>" class="box">
                <span>Actualiza tu foto de perfil :</span>
                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
            </div>
            <div class="inputBox">
                <input type="hidden" name="old_pass" value="<?php echo htmlspecialchars($fetch['password'] ?? ''); ?>">
                <span>Contraseña anterior :</span>
                <input type="password" name="update_pass" placeholder="Introduce la contraseña anterior" class="box">
                <span>Nueva contraseña :</span>
                <input type="password" name="new_pass" placeholder="Introduce la nueva contraseña" class="box">
                <span>Confirma la nueva contraseña :</span>
                <input type="password" name="confirm_pass" placeholder="Confirma la nueva contraseña" class="box">
            </div>
        </div>
        <input type="submit" value="Actualizar perfil" name="update_profile" class="btn">
        <a href="home.php" class="delete-btn">Regresar</a>
    </form>
</div>

</body>
</html>
