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
   <link rel="stylesheet" href="/Banco/public/css/seguimiento.css">

   <!-- FontAwesome -->
   <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
   <script src="/Banco/node_modules/jquery/dist/jquery.min.js"></script>

   <script src="/Banco/app/modules/select2/select2.min.js"></script>
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
         <a href="/Banco/public/layouts/modificarStock">Modificar stock</a>
         <a href="/Banco/public/layouts/seguimientoSolicitudes">Seguimiento</a>
         <a href="/Banco/public/layouts/realizarPedido" class="disabled">Realizar pedido</a>
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
            <a class="profile" href="/Banco/public/layouts/profile">Ir a mi perfil</a>
            <a style="color: red;" href="/Banco/app/db/logout"><i class="fa-solid fa-power-off"></i> Cerrar sesión</a>
         </div>
      </div>
   </header>



   <article>

      <div class="topDiv">
         <div class="banco">
            Banco:
            <select name="banco" id="bancoSelect">
               <option value="" selected disabled>Seleccione una opción</option>
               <?php
               try {
                  $stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
                  $stmt->execute();

                  $options = "";
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                     $banco = $row['banco'];
                     $siglas = $row['siglas'];
                     $options .= "<option value='$siglas'>$banco - $siglas</option>";
                  }

                  // Escribir las opciones en el DOM
                  echo $options;
               } catch (PDOException $e) {
                  echo 'Error: ' . $e->getMessage();
               }
               ?>
            </select>
         </div>

         <div class="news">
            <a class="" id="newA" href="/Banco/public/layouts/getForm"><i class="fa-solid fa-receipt"></i></a>
         </div>
      </div>







      <div class="tabla" style="width: 100%; display: flex; justify-content: center;">
         <table style="max-width: 95%;">
            <thead>
               <tr>
                  <th>N° de solicitud</th>
                  <th>Tipo de solicitud</th>
                  <th>Fecha de solicitud</th>
                  <th>Expediente GDEBA</th>
                  <th>Paciente</th>
                  <th>DNI</th>
                  <th>Estado</th>
                  <th>Tipo de cirugía</th>
                  <th>Acción</th>
               </tr>
            </thead>

            <tbody>
               <!-- Fila de mensaje si no se ha seleccionado un banco -->
               <tr id="nulo">
                  <td colspan="9" style="text-align: center;"><b style="font-size: 2vw;">Seleccione un banco de la lista para continuar</b></td>
               </tr>

               <?php
               // Solo realizar la consulta y mostrar las solicitudes si se ha seleccionado un banco
               if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['banco'])) {
                  // Obtener el valor del banco seleccionado
                  $bancoSeleccionado = $_POST['banco'];

                  // Definir la consulta SQL para obtener las solicitudes filtradas por banco
                  $solicitudesQuery = "SELECT id, solicitud, tipo_solicitud, DATE_FORMAT(fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud, GDEBA, paciente, dni, estado, tipo_cirugia, intervencion, banco FROM solicitudes WHERE intervencion = 'si' AND banco = :bancoSeleccionado ORDER BY id ASC";

                  // Preparar y ejecutar la consulta para obtener las solicitudes filtradas por banco
                  $solicitudesStatement = $pdo->prepare($solicitudesQuery);
                  $solicitudesStatement->bindParam(':bancoSeleccionado', $bancoSeleccionado, PDO::PARAM_STR);
                  $solicitudesStatement->execute();
                  $solicitudes = $solicitudesStatement->fetchAll(PDO::FETCH_ASSOC);

                  // Mostrar las solicitudes filtradas
                  foreach ($solicitudes as $solicitud) :
               ?>
                     <tr class="tablaPedidos">
                        <!-- ... (celdas de la fila) ... -->
                     </tr>
               <?php
                  endforeach;

                  // Ocultar el mensaje de "Seleccione un banco" y las filas iniciales
                  echo "<script>nulo.style.display = 'none';</script>";
               }
               ?>
            </tbody>
         </table>
         <div class="finish">
            
         </div>
      </div>







   </article>



   <footer>
      &copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
   </footer>

</body>

<script src="/Banco/public/js/header.js"></script>
<script src="/Banco/public/js/seguimiento.js"></script>

</html>