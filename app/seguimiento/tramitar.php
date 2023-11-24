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

// Verificar si se ha enviado un ID válido a través de la URL
if (isset($_GET['idSol']) && is_numeric($_GET['idSol'])) {
  $idSol = $_GET['idSol'];

  // Consulta SQL para obtener el ID y el estado actual del item
  $query = "SELECT id, solicitud, tipo_solicitud, fecha_solicitud, GDEBA, items_JSON, paciente, dni, estado, tipo_cirugia, fecha_perfeccionamiento, sol_provision, fecha_cirugia, comentarios, intervencion FROM solicitudes WHERE id = :idSol";

  // Preparar la sentencia
  $statement = $pdo->prepare($query);

  // Bind del valor del ID
  $statement->bindParam(':idSol', $idSol, PDO::PARAM_INT);

  // Ejecutar la consulta
  if ($statement->execute()) {
    // Obtener el resultado de la consulta
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $solicitud = $result['solicitud'];
    $tipo_solicitud = $result['tipo_solicitud'];
    $fecha_solicitud = $result['fecha_solicitud'];
    $GDEBA = $result['GDEBA'];
    $items_JSON = $result['items_JSON'];
    $paciente = $result['paciente'];
    $dni = $result['dni'];
    $estado = $result['estado'];
    $tipo_cirugia = $result['tipo_cirugia'];
    $fecha_perfeccionamiento = $result['fecha_perfeccionamiento'];
    $sol_provision = $result['sol_provision'];
    $fecha_cirugia = $result['fecha_cirugia'];
    $comentarios = $result['comentarios'];

    if ($result) {
      $intervencion = $result['intervencion'];

      // Verificar si el estado actual es diferente a "act"
      if ($intervencion == 'no') {
        // Obtener el ID para la actualización
        $id = $result['id'];

        // Consulta SQL para actualizar el estado a "act"
        $updateQuery = "UPDATE solicitudes SET intervencion = 'si' WHERE id = :id";

        // Preparar la sentencia de actualización
        $updateStatement = $pdo->prepare($updateQuery);

        // Bind del valor del ID para la actualización
        $updateStatement->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta de actualización
        $updateStatement->execute();
      }
    }
  }
}


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
  <link rel="stylesheet" href="/Banco/public/css/tramitar.css">
  <link rel="stylesheet" href="/Banco/public/css/table.css">

  <!-- FontAwesome -->
  <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
  <article>
    <form action="/Banco/app/seguimiento/tramitarForm.php" method="post">
      <input type="hidden" name="id" value="<?php echo $idSol ?>">
      <div class="paciente modulo">
        <h3><u>Datos del paciente</u></h3>

        <div class="datos">
          <div>
            <label for="fecha_solicitud">Fecha de solicitud</label>
            <input value="<?php echo $fecha_solicitud ?>" type="date" id="fecha_solicitud" name="fecha_solicitud" readonly>
          </div>
          <div>
            <label for="paciente">Nombre del paciente</label>
            <input type="text" id="paciente" placeholder="Nombre completo del paciente" name="paciente" value="<?php echo $paciente ?>">
          </div>
          <div>
            <label for="dni">D.N.I.</label>
            <input type="text" id="dni" name="dni" oninput="formatDNI(this)" placeholder="DNI del paciente" value="<?php echo $dni ?>">
          </div>
        </div>
      </div>
      <div class="intranet modulo">
        <h3><u>Datos de intranet</u></h3>

        <div class="datos" style="margin-bottom: 2vw;">
          <div>
            <label for="solicitud">Solicitud de intranet N°</label>
            <input type="number" id="solicitud" name="solicitud" placeholder="95212" value="<?php echo $solicitud ?>">
          </div>
          <div>
            <label for="tipo_solicitud">Tipo de solicitud</label>
            <select name="tipo_solicitud" id="tipo_solicitud">
              <?php
              // Valores disponibles para el tipo de solicitud
              $opciones = [
                'Para cirugía',
                'Para stock',
                'Para nominalizar stock',
                'Para nominalizar stock hospitalario'
              ];

              // Verificar si el valor está en blanco y generar el option correspondiente
              $selected = ($tipo_solicitud === '') ? 'selected' : '';
              echo '<option value="" disabled ' . $selected . '>Seleccionar tipo de solicitud</option>';

              // Recorrer las opciones y generar el HTML del <select>
              foreach ($opciones as $opcion) {
                // Verificar si la opción coincide con el valor de la base de datos
                $selected = ($tipo_solicitud == $opcion) ? 'selected' : '';

                // Imprimir la opción con la marca "selected" si es necesario
                echo "<option value=\"$opcion\" $selected>$opcion</option>";
              }
              ?>
            </select>
          </div>
          <div>
            <label for="tipo_cirugia">Tipo de cirugía</label>
            <select name="tipo_cirugia" id="tipo_cirugia">
              <?php
              // Valores disponibles para el tipo de solicitud
              $opciones = [
                'Urgencia',
                'Programada'
              ];

              // Verificar si el valor está en blanco y generar el option correspondiente
              $selected = ($tipo_cirugia === '') ? 'selected' : '';
              echo '<option value="" disabled ' . $selected . '>Seleccionar tipo de cirugía</option>';

              // Recorrer las opciones y generar el HTML del <select>
              foreach ($opciones as $opcion) {
                // Verificar si la opción coincide con el valor de la base de datos
                $selected = ($tipo_solicitud == $opcion) ? 'selected' : '';

                // Imprimir la opción con la marca "selected" si es necesario
                echo "<option value=\"$opcion\" $selected>$opcion</option>";
              }
              ?>
            </select>
          </div>
          <div>
            <label for="fecha_perfeccionamiento">Fecha de perfeccionamiento</label>
            <input type="date" id="fecha_perfeccionamiento" name="fecha_perfeccionamiento" value="<?php echo $fecha_perfeccionamiento ?>">
          </div>
          <div>
            <label for="sol_provision">Provisión de solicitud</label>
            <input type="text" id="sol_provision" name="sol_provision" placeholder="99-0577-SPR21" value="<?php echo $sol_provision ?>">
          </div>
          <div>
            <label for="fecha_cirugia">Fecha de cirugía</label>
            <input type="date" id="fecha_cirugia" name="fecha_cirugia" value="<?php echo $fecha_cirugia ?>">
          </div>
        </div>


        <label class="labelJSON" for="items_JSON">Items y cantidad</label>
        <table style="margin-top: .8vw;">
          <?php

          // Decodificar el JSON
          $items_array = json_decode($items_JSON, true);

          // Cabecera de la tabla HTML
          $html = '<thead>
            <tr>
              <th>Item</th>
              <th>Nombre</th>
              <th>Descripcion</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>';

          // Recorrer cada elemento del array
          foreach ($items_array as $item) {
            // Obtener el ID y la cantidad del array
            $id = $item['id'];
            $cantidad = $item['cantidad'];

            // Realizar la consulta para obtener la información del item
            $query = "SELECT item, nombre, d_corta FROM items WHERE id = :id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener el resultado de la consulta
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró la información
            if ($result) {
              // Extraer la información
              $item_nombre = $result['item'];
              $nombre = $result['nombre'];
              $descripcion = $result['d_corta'];

              // Agregar la fila a la tabla HTML
              $html .= '<tr>';
              $html .= '<td>' . $item_nombre . '</td>';
              $html .= '<td>' . $nombre . '</td>';
              $html .= '<td>' . $descripcion . '</td>';
              $html .= '<td>' . $cantidad . '</td>';
              $html .= '</tr>';
            } else {
              // Manejar el caso donde no se encontró información para el ID
              $html .= '<tr>';
              $html .= '<td colspan="4">No se encontró información para el ID ' . $id . '</td>';
              $html .= '</tr>';
            }
          }

          // Cierre de la tabla HTML
          $html .= '</tbody>';

          // Mostrar la tabla HTML
          echo $html;
          ?>


          </tbody>
        </table>

      </div>
      <div class="gdeba modulo">
        <h3><u>Datos de GDEBA</u></h3>

        <div class="divModulo">
          <label for="GDEBA">N° de GDEBA</label>
          <input type="text" id="GDEBA" name="GDEBA" placeholder="EX-2023-61786524- -GDEBA-HIGAGSMMSALGP" minlength="25" value="<?php echo $GDEBA ?>">
          <p class="error-message" id="error-message"></p>
        </div>
      </div>
      <div class="otros modulo">
        <h3><u>Otros datos</u></h3>

        <div class="datos" style="grid-template-columns: repeat(2, 1fr);">
          <div>
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
              <?php
              // Valores disponibles para el tipo de solicitud
              $opciones = [
                'Con GDEBA',
                'Nominalizada',
                'Pendiente',
                'Perfeccionada',
                'Rescindida',
                'Anulada',
                'Facturada'
              ];

              // Verificar si el valor está en blanco y generar el option correspondiente
              $selected = ($tipo_cirugia === '') ? 'selected' : '';
              echo '<option value="" disabled ' . $selected . '>Seleccionar estado</option>';

              // Recorrer las opciones y generar el HTML del <select>
              foreach ($opciones as $opcion) {
                // Verificar si la opción coincide con el valor de la base de datos
                $selected = ($tipo_solicitud == $opcion) ? 'selected' : '';

                // Imprimir la opción con la marca "selected" si es necesario
                echo "<option value=\"$opcion\" $selected>$opcion</option>";
              }
              ?>
            </select>
          </div>
          <div>
            <label for="comentarios">Comentarios</label>
            <textarea name="comentarios" id="comentarios" cols="30" rows="10" value="<?php echo $comentarios ?>"></textarea>
          </div>
        </div>
      </div>


      <button type="submit" class="btn-verde"><i class="fa-solid fa-floppy-disk"></i> Registrar cambios</button>
    </form>
  </article>

  <script src="/Banco/public/js/tramitar.js"></script>
</body>

</html>