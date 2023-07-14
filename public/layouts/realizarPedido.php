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
   <title>S.C.S. - Mi usuario</title>
   <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
   <link rel="stylesheet" href="/Banco/public/css/base.css">
   <link rel="stylesheet" href="/Banco/public/css/header.css">
   <link rel="stylesheet" href="/Banco/public/css/realizarPedido.css">

   <!-- FontAwesome -->
   <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
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
            <a style="color: red;" href="/Banco/app/db/logout.php"><i class="fa-solid fa-power-off"></i> Cerrar
               sesión</a>
         </div>
      </div>
   </header>

   <article>
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
                  $id_banco = $row['id'];
                  $banco = $row['banco'];
                  $siglas = $row['siglas'];
                  $options .= "<option value='$id_banco'>$banco - $siglas</option>";
               }

               // Escribir las opciones en el DOM
               echo $options;
            } catch (PDOException $e) {
               echo 'Error: ' . $e->getMessage();
            }
            ?>
         </select>
      </div>
      <hr>

      <div class="formContainer" id="formContainer"></div>




      <style>
         .solicitud {
            border: .2vw #6d6d6d solid;
            padding: 1vw;
            margin-top: 3vh;
            width: 80%;
         }

         .cabecera {
            height: 15vh;
            display: flex;
            flex-direction: row;
            align-content: center;
            justify-content: space-around;
            align-items: center;
         }

         .cabecera img {
            height: 15vh;
            width: auto;
         }

         .cabecera h1 {
            text-decoration: underline;
         }

         .datosPaciente {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
         }
      </style>

      <div class="solicitud">
         <form action="">
            <div class="cabecera">
               <img src="/Banco/public/image/higaLogo.png" alt="H.I.G.A. General San Martín - Logo">
               <h1>Solicitud de autorización para la compra de prótesis</h1>
            </div>
            <div class="datosPaciente">
               <div style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-between;">
                  <div>
                     <label for="paciente">Paciente:</label>
                     <input type="text" name="paciente" id="paciente" required>
                  </div>

                  <div>
                     <label for="fecha">Fecha:</label>
                     <input type="date" name="fecha" id="fecha" required>
                  </div>
               </div>

               <div>
                  <label for="edad">Edad:</label>
                  <input type="number" name="edad" id="edad" required>
               </div>

               <div>
                  <label for="documento">Documento:</label>
                  <input type="text" name="documento" id="documento" required>
               </div>
            </div>
            <div class="datosCirugia" style="width: 100%;">
               <div style="width: 100%;">
                  <label for="busqueda">Diagnostico:</label>
                  <input type="text" id="busqueda" placeholder="Buscar y seleccionar...">
                  <select id="opciones" required style="width: 75%;">
                     <option value="" selected disabled>Seleccione una opción</option>
                  </select>

                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script>
                     $(document).ready(function() {
                        // Cargar opciones iniciales
                        cargarOpciones('');

                        // Evento de cambio en el campo de búsqueda
                        $('#busqueda').on('input', function() {
                           var searchTerm = $(this).val().trim();
                           cargarOpciones(searchTerm);
                        });

                        // Función para cargar opciones mediante AJAX
                        function cargarOpciones(searchTerm) {
                           $.ajax({
                              url: '/Banco/public/layouts/buscar_opciones.php', // Archivo PHP que maneja la búsqueda y retorna resultados
                              method: 'POST',
                              data: {
                                 searchTerm: searchTerm
                              },
                              dataType: 'json',
                              success: function(data) {
                                 // Limpiar opciones existentes
                                 $('#opciones').empty();
                                 $('#opciones').append('<option value="" selected disabled>Seleccione una opción</option>');

                                 // Agregar nuevas opciones
                                 $.each(data, function(index, option) {
                                    $('#opciones').append('<option value="' + option.clave + ' - ' + option.descripcion + '">' + option.clave + ' - ' + option.descripcion + '</option>');
                                 });
                              },
                              error: function(xhr, status, error) {
                                 console.log('Error en la solicitud AJAX: ' + error);
                              }
                           });
                        }
                     });
                  </script>
               </div>
            </div>
            <!-- <script src="/Banco/public/js/realizarPedidoForm.js"></script> -->
         </form>
      </div>

   </article>

   <footer>
      &copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
   </footer>
</body>
<script src="/Banco/public/js/header.js"></script>
<script src="/Banco/public/js/realizarPedido.js"></script>

</html>