<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
   exit();
}

if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header('location:login.php');
   exit();
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
         $select = $conn->prepare("SELECT * FROM `user_form` WHERE id = ?");
         $select->execute([$user_id]);
         $fetch = $select->fetch(PDO::FETCH_ASSOC);
         
         if(!$fetch) {
            echo 'Usuario no encontrado';
         }
         echo '<img src="'.($fetch['image'] ? 'uploaded_img/'.$fetch['image'] : 'images/default-avatar.png').'" alt="Imagen">';
      ?>
      <h3><?php echo htmlspecialchars($fetch['name']); ?></h3>
      <a href="update_profile.php" class="btn">Actualización del perfil</a>
      <a href="gallery.php" class="btn">Mi galería de imágenes</a>
      <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn">Salir</a>
      <p>Regresar al <a href="login.php">Ingreso</a> o al <a href="register.php">Registro</a></p>
   </div>

</div>

</body>
</html>
