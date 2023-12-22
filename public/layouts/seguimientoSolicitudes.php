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

$titulo_pestaña = "Seguimiento";

?>

<?php include_once 'bases/header.php'; ?>
<link rel="stylesheet" href="/Banco/public/css/seguimiento.css">

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
   </div>

   <style>
      .fondo {
         background-color: #0000007a;
         position: fixed;
         width: 100%;
         height: 100%;
         top: 0;
         z-index: 10000;
      }

      .archive {
         display: flex;
         flex-direction: column;
         background-color: #dddddd;
         padding: 1vw;
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         border-radius: .8vw;
         border: .2vw #000 solid;
      }

      .botonesArchi {
         display: flex;
         flex-direction: row;
         justify-content: space-evenly;
         align-items: center;
         margin-top: 1vw;
      }

      .botonesArchi .btn-rojo,
      .botonesArchi .btn-verde {
         margin: 0;
         width: 10vw
      }
   </style>

   <div class="fondo" id="fondoArchive" style="display: none;">
      <div class="archive">
         <h3 style="font-size: 1.5vw; margin-top: 1.5vw;">¿Está seguro que desea archivar este expediente?</h3>

         <div class="datosExpediente" style="margin: 1.5vw 0;">
            <p style="font-size: 1.2vw;"><b>Expediente:</b> <span id="expedienteTexto"></span></p>
            <p style="font-size: 1.2vw;"><b>Nombre del paciente:</b> <span id="nombrePacienteTexto"></span></p>
            <p style="font-size: 1.2vw;"><b>D.N.I:</b> <span id="dniTexto"></span></p>
         </div>

         <div class="botonesArchi">
            <button class="btn-verde" onclick="cerrarDialogoArchivo()"><i class="fa-solid fa-x"></i> Cancelar</button>
            <a style="text-decoration: none; text-align: center;" class="btn-rojo" href="#" id="archivarBTN"><i class="fa-solid fa-box-archive"></i> Archivar</a>
         </div>
      </div>
   </div>

   <script>
      function dialogoArchivo(id, GDEBA, paciente, dni) {
         // Actualizar los datos del expediente en el diálogo
         document.getElementById('expedienteTexto').innerText = GDEBA;
         document.getElementById('nombrePacienteTexto').innerText = paciente;
         document.getElementById('dniTexto').innerText = dni;
         document.getElementById('archivarBTN').href = '/Banco/app/seguimiento/archivar_solicitud.php?solicitudId=' + id;

         // Mostrar el fondo del diálogo
         fondoArchive.style.display = 'flex';
      }

      function cerrarDialogoArchivo() {
         // Ocultar el fondo del diálogo
         fondoArchive.style.display = 'none';
      }
   </script>








</article>



<?php include_once 'bases/footer.php'; ?>

</body>

<script src="/Banco/public/js/seguimiento.js"></script>

</html>