<?php

include 'config.php';
session_start(); // Asegúrate de iniciar la sesión

if(isset($_POST['submit'])){
    // Sanitizar datos de entrada
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $image = file_get_contents($_FILES['image']['tmp_name']);
    
    // Verificar si el tipo de imagen es válido
    $allowed_types = ['image/jpeg', 'image/png'];
    $image_type = mime_content_type($_FILES['image']['tmp_name']);
    
    if (!in_array($image_type, $allowed_types)) {
        $message[] = 'Tipo de imagen no válido. Solo se permiten JPEG y PNG.';
    } else {
        // Verificar si el usuario ya existe
        try {
            $stmt = $conn->prepare("SELECT * FROM `user_form` WHERE email = :email AND password = :password");
            $stmt->execute([':email' => $email, ':password' => $pass]);
            if($stmt->rowCount() > 0){
                $message[] = 'El usuario ya existe'; 
            } else {
                if($pass != $cpass){
                    $message[] = '¡Las contraseñas no coinciden!';
                } else {
                    // Insertar nuevo usuario
                    $stmt = $conn->prepare("INSERT INTO `user_form` (name, email, password, image) VALUES (:name, :email, :password, :image)");
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $pass);
                    $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
                    $insert = $stmt->execute();

                    if($insert){
                        $message[] = '¡Registrado exitosamente!';
                        header('Location: login.php');
                        exit();
                    } else {
                        $message[] = '¡Error en el registro!';
                    }
                }
            }
        } catch (PDOException $e) {
            die('Query failed: ' . $e->getMessage());
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
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="form-container">
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Registrar ahora</h3>
        <?php
        if(isset($message)){
            foreach($message as $msg){
                echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
            }
        }
        ?>
        <input type="text" name="name" placeholder="Nombre de usuario" class="box" required>
        <input type="email" name="email" placeholder="Email" class="box" required>
        <input type="password" name="password" placeholder="Contraseña" class="box" required>
        <input type="password" name="cpassword" placeholder="Confirma la contraseña" class="box" required>
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
        <input type="submit" name="submit" value="Registrarme" class="btn">
        <p>¿Ya tienes una cuenta registrada? <a href="login.php">Ingresa con tu cuenta</a></p>
    </form>
</div>

</body>
</html>
