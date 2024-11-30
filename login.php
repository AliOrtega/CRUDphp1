<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bienvenido</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<div class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Ingresar</h3>
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<div class="message">'.$msg.'</div>';
         }
      }
      ?>
      <input type="email" name="email" placeholder="Email" class="box" required autocomplete="email">
      <input type="password" name="password" placeholder="Contraseña" class="box" required autocomplete="current-password">
      <input type="submit" name="submit" value="Ingresar" class="btn">
      <p>¿No tienes una cuenta? <a href="register.php">Registrate</a></p>
      <p><a href="https://www.youtube.com/watch?v=6oV9sJdDv4E">Tutorial de cómo registrarse</a></p>
   </form>

</div>

</body>
</html>
