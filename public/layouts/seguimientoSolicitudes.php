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
               $stmt = $pdo->prepare("SELECT banco, siglas FROM bancos");
               $stmt->execute();

               $options = "";
               $userBanco = $user->getBanco();
               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $banco = $row['banco'];
                  $siglas = $row['siglas'];
                  $selected = ($userBanco == $siglas) ? "selected" : "";

                  $options .= "<option value='$siglas' $selected>$banco - $siglas</option>";
               }

               // Verificar si el banco almacenado no está en la lista
               $stmt->execute(); // Reiniciar el cursor del conjunto de resultados
               $found = false;
               while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  if ($row['siglas'] == $userBanco) {
                     $found = true;
                     break;
                  }
               }

               // Agregar opción para "Otro" si el banco almacenado no está en la lista
               if (!$found && $userBanco != "") {
                  $options .= "<option value='' selected>Otro</option>";
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

</article>



<?php include_once 'bases/footer.php'; ?>

</body>

<script src="/Banco/public/js/seguimiento.js"></script>

</html>