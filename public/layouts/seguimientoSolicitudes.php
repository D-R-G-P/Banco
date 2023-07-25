<?php

require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

$user = new User();
$userSession = new UserSession();
$currentUser = $userSession->getCurrentUser();
$user->setUser($currentUser);

$db = new DB();
$pdo = $db->connect();

?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>S.C.S. - Seguimiento</title>
   <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
   <link rel="stylesheet" href="/Banco/public/css/base.css">
   <link rel="stylesheet" href="/Banco/public/css/header.css">

   <!-- FontAwesome -->
   <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
   <?php
   if (isset($_SESSION['success_message'])) {
      echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
      // Borrar el mensaje de éxito de la variable de sesión para no mostrarlo nuevamente
      unset($_SESSION['success_message']);
   }
   if (isset($_SESSION['error_message'])) {
      echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
      // Borrar el mensaje de éxito de la variable de sesión para no mostrarlo nuevamente
      unset($_SESSION['error_message']);
   }
   ?>

   <header>
      <div class="logo">
         <a href="/Banco/"><i class="fa-solid fa-dolly"></i></a>
      </div>

      <div class="links">
         <a href="/Banco/">Inicio</a>
         <a href="/Banco/public/layouts/modificarStock.php">Modificar stock</a>
         <a href="/Banco/public/layouts/seguimientoSolicitudes.php" class="disabled">Seguimiento</a>
         <a href="/Banco/public/layouts/realizarPedido.php" class="disabled">Realizar pedido</a>
      </div>

      <button id="user" class="user BORON">
         <i id="userI" class="fa-solid fa-user BORON"></i>
         <i id="flecha" class="fa-solid fa-caret-down BORON"></i>
      </button>

      <div id="userOptions" class="userOptions BORON">
         <div class="datos">
            <div>
               Bienvenido/a <br>
               <?php echo $user->getNombre() . " " . $user->getApellido(); ?>
            </div>
            <div>
               Perfil: <br>
               <?php echo $user->getTipo_usuario() ?>
            </div>
            <div>
               Cargo: <br>
               <?php echo $user->getCargo() ?>
            </div>

         </div>
         <div class="botones">
            <a class="profile" href="/Banco/public/layouts/profile.php">Ir a mi perfil</a>
            <a style="color: red;" href="/Banco/app/db/logout.php"><i class="fa-solid fa-power-off"></i> Cerrar sesión</a>
         </div>
      </div>
   </header>



   <article>

   

   </article>



   <footer>
      &copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
   </footer>

</body>

<script src="/Banco/public/js/header.js"></script>

</html>