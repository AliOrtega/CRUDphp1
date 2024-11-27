<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $update_name = htmlspecialchars($_POST['update_name']);
    $update_email = htmlspecialchars($_POST['update_email']);

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
                $message[] = 'old password not matched!';
            } elseif ($new_pass != $confirm_pass) {
                $message[] = 'confirm password not matched!';
            } else {
                $stmt = $conn->prepare("UPDATE `user_form` SET password = :password WHERE id = :id");
                $stmt->execute(['password' => $confirm_pass, 'id' => $user_id]);
                $message[] = 'password updated successfully!';
            }
        }

        // Manejo de imagen
        $update_image = $_FILES['update_image']['name'];
        $update_image_size = $_FILES['update_image']['size'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'uploaded_img/'.$update_image;

        // Crear el directorio si no existe con permisos correctos
        if (!is_dir('uploaded_img')) {
            mkdir('uploaded_img', 0755, true);
        }

        if (!empty($update_image)) {
            if ($update_image_size > 2000000) {
                $message[] = 'image is too large';
            } else {
                if (move_uploaded_file($update_image_tmp_name, $update_image_folder)) {
                    $stmt = $conn->prepare("UPDATE `user_form` SET image = :image WHERE id = :id");
                    $stmt->execute(['image' => $update_image, 'id' => $user_id]);
                    $message[] = 'image updated successfully!';
                } else {
                    $message[] = 'Failed to upload image!';
                }
            }
        }
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
    <title>Update Profile</title>
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
            die('Query failed: ' . $e->getMessage());
        }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <?php
            if ($fetch['image'] == '') {
                echo '<img src="images/default-avatar.png">';
            } else {
                echo '<img src="uploaded_img/'.$fetch['image'].'">';
            }
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '<div class="message">'.$msg.'</div>';
                }
            }
        ?>
        <div class="flex">
            <div class="inputBox">
                <span>Nombre de usuario :</span>
                <input type="text" name="update_name" value="<?php echo htmlspecialchars($fetch['name']); ?>" class="box">
                <span>Email :</span>
                <input type="email" name="update_email" value="<?php echo htmlspecialchars($fetch['email']); ?>" class="box">
                <span>Actualiza tu foto de perfil :</span>
                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
            </div>
            <div class="inputBox">
                <input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
                <span>Contraseña anterior :</span>
                <input type="password" name="update_pass" placeholder="enter previous password" class="box">
                <span>Nueva contraseña :</span>
                <input type="password" name="new_pass" placeholder="enter new password" class="box">
                <span>Confirma la nueva contraseña :</span>
                <input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
            </div>
        </div>
        <input type="submit" value="update profile" name="update_profile" class="btn">
        <a href="home.php" class="delete-btn">Regresar</a>
    </form>
</div>

</body>
</html>
