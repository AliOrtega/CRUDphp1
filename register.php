<?php

include 'config.php';

if(isset($_POST['submit'])){
   // Sanitize input data
   $name = htmlspecialchars($_POST['name']);
   $email = htmlspecialchars($_POST['email']);
   $pass = md5($_POST['password']);
   $cpass = md5($_POST['cpassword']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   // Check if user already exists
   try {
       $stmt = $conn->prepare("SELECT * FROM `user_form` WHERE email = :email AND password = :password");
       $stmt->execute(['email' => $email, 'password' => $pass]);
       if($stmt->rowCount() > 0){
          $message[] = 'User already exists'; 
       }else{
          if($pass != $cpass){
             $message[] = 'Confirm password does not match!';
          }elseif($image_size > 2000000){
             $message[] = 'Image size is too large!';
          }else{
             // Insert new user
             $stmt = $conn->prepare("INSERT INTO `user_form` (name, email, password, image) VALUES (:name, :email, :password, :image)");
             $insert = $stmt->execute(['name' => $name, 'email' => $email, 'password' => $pass, 'image' => $image]);

             if($insert){
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Registered successfully!';
                header('Location: login.php');
             }else{
                $message[] = 'Registration failed!';
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
            echo '<div class="message">'.$msg.'</div>';
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
