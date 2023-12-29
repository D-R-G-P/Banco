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
  $query = "SELECT id, solicitud, tipo_solicitud, fecha_solicitud, GDEBA, items_JSON, paciente, dni, estado, tipo_cirugia, fecha_perfeccionamiento, sol_provision, fecha_cirugia, comentarios, nomencladores, categoriascie, intervencion, domicilio, localidad, sexo, edad, tipoDoc, telefono, intervencion FROM solicitudes WHERE id = :idSol";

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
    $nomencladores = $result['nomencladores'];
    $categoriascie = $result['categoriascie'];
    $domicilio = $result['domicilio'];
    $localidad = $result['localidad'];
    $sexo = $result['sexo'];
    $edad = $result['edad'];
    $tipoDoc = $result['tipoDoc'];
    $telefono = $result['telefono'];
    $intervencion = $result['intervencion'];

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

  <script>
    function copyTextAndAnimate(button, textToCopy) {
      // Copiar el texto al portapapeles
      navigator.clipboard.writeText(textToCopy);

      // Obtener el texto completo del botón
      const buttonText = button.textContent;

      // Cambiar el contenido del botón a un ícono por 3 segundos
      button.innerHTML = '<i class="fa-solid fa-check"></i>';

      setTimeout(() => {
        // Volver al contenido original del botón después de 3 segundos
        button.innerHTML = buttonText;
      }, 3000);
    }
  </script>
</head>

<body>
  <article>
    <?php 
    
    if ($intervencion == "archivo") {
      echo '<div class="notiswarning">
      ¡ATENCIÓN! El expediente al que accedió se encuentra archivado.
    </div>';
    }

    ?>
    <div class="print modulo">
      <h3 style="margin-bottom: 0;">Planillas descargables:</h3>
      <div class="botones">
        <a href="/Banco/app/seguimiento/planillas/prescripcion.php?idSol=<?php echo $idSol ?>" target="_blank" class="btn-verde"><i class="fa-solid fa-file"></i> Prescripción</a>
        <a href="/Banco/app/seguimiento/planillas/samo.php?idSol=<?php echo $idSol ?>" target="_blank" class="btn-verde"><i class="fa-solid fa-pager"></i> SAMO</a>
      </div>
    </div>
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
          <div>
            <label for="domicilio">Domicilio</label>
            <input type="text" id="domicilio" name="domicilio" placeholder="1 y 70" value="<?php echo $domicilio ?>">
          </div>
          <div>
            <label for="localidad">Localidad</label>
            <input type="text" id="localidad" name="localidad" placeholder="La Plata" value="<?php echo $localidad ?>">
          </div>
          <div>
            <label for="sexo">Sexo</label>
            <select name="sexo" id="sexo">
              <?php
              // Valores disponibles para el tipo de solicitud
              $opciones = [
                'Masculino',
                'Femenino',
                'X'
              ];

              // Verificar si el valor está en blanco y generar el option correspondiente
              $selected = ($sexo === '') ? 'selected' : '';
              echo '<option value="" disabled ' . $selected . '>Seleccionar estado</option>';

              // Recorrer las opciones y generar el HTML del <select>
              foreach ($opciones as $opcion) {
                // Verificar si la opción coincide con el valor de la base de datos
                $selected = ($sexo == $opcion) ? 'selected' : '';

                // Imprimir la opción con la marca "selected" si es necesario
                echo "<option value=\"$opcion\" $selected>$opcion</option>";
              }
              ?>
            </select>
          </div>
          <div>
            <label for="edad">Edad</label>
            <input type="number" name="edad" id="edad" placeholder="43" value="<?php echo $edad ?>">
          </div>
          <div>
            <label for="tipoDoc">Tipo de documento</label>
            <select name="tipoDoc" id="tipoDoc">
              <?php
              // Valores disponibles para el tipo de solicitud
              $opciones = [
                'L.E.',
                'L.C.',
                'C.I.',
                'D.N.I.',
                'Otro'
              ];

              // Verificar si el valor está en blanco y generar el option correspondiente
              $selected = ($tipoDoc === '') ? 'selected' : '';
              echo '<option value="" disabled ' . $selected . '>Seleccionar estado</option>';

              // Recorrer las opciones y generar el HTML del <select>
              foreach ($opciones as $opcion) {
                // Verificar si la opción coincide con el valor de la base de datos
                $selected = ($tipoDoc == $opcion) ? 'selected' : '';

                // Imprimir la opción con la marca "selected" si es necesario
                echo "<option value=\"$opcion\" $selected>$opcion</option>";
              }
              ?>
            </select>
          </div>
          <div>
            <label for="telefono">Telefono</label>
            <input type="number" name="telefono" id="telefono" maxlength="14" value="<?php echo $telefono ?>">


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
                $selected = ($tipo_cirugia == $opcion) ? 'selected' : '';

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
        <h3><u>Otros datos del expediente</u></h3>

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
            <textarea name="comentarios" id="comentarios" cols="30" rows="10"><?php echo $comentarios ?></textarea>
          </div>
        </div>
      </div>

      <div class="modulo medico">
        <h3><u>Datos médicos</u></h3>

        <div class="datos" style="grid-template-columns: repeat(2, 1fr);">

          <div>
            <label for="nomencladores">Nomenclador de cirugía</label>
            <input type="text" list="nomencladores" name="nomencladores" placeholder="Escribe para buscar..." value="<?php echo $nomencladores ?>">
            <datalist id="nomencladores">
              <option value="" selected disabled>Seleccionar una opción</option>
              <?php
              try {
                $stmt = $pdo->prepare("SELECT codigo, descripcion FROM nomencladorescx");
                $stmt->execute();

                $options = "";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $codigo = $row['codigo'];
                  $descripcion = $row['descripcion'];
                  $options .= "<option value='$codigo - $descripcion'>$codigo - $descripcion</option>";
                }

                // Escribir las opciones en el DOM
                echo $options;
              } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
              }
              ?>
            </datalist>
          </div>

          <div>
            <label for="categoriascie">Diagnostico CIE-10</label>
            <input type="text" list="categoriascie" name="categoriascie" placeholder="Escribe para buscar..." value="<?php echo $categoriascie ?>">
            <datalist id="categoriascie">
              <option value="" selected disabled>Seleccionar una opción</option>
              <?php
              try {
                $stmt = $pdo->prepare("SELECT clave, descripcion FROM categoriascie10");
                $stmt->execute();

                $options = "";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $clave = $row['clave'];
                  $descripcion = $row['descripcion'];
                  $options .= "<option value='$clave - $descripcion'>$clave - $descripcion</option>";
                }

                // Escribir las opciones en el DOM
                echo $options;
              } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
              }
              ?>
            </datalist>
          </div>
        </div>
      </div>


      <button type="submit" class="btn-verde"><i class="fa-solid fa-floppy-disk"></i> Registrar cambios</button>
    </form>

    <h3 class="fa-texto" id="fa-texto" style="color: #fff;" onclick="panel()">Referencias</h3>
    <div class="panel" id="panel">
      <div class="copy">
        <h4 style="padding: 0 .5vw .5vw .5vw; font-size: 1.5vw;">Referencias</h4>
        <div class="copys">
          <button onclick="copyTextAndAnimate(this, 'Prescripción <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">1. Prescripción</button>
          <button onclick="copyTextAndAnimate(this, 'Solicitud intranet <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">2. Solicitud intranet</button>
          <button onclick="copyTextAndAnimate(this, 'SAMO <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">3. S.A.M.O.</button>
          <button onclick="copyTextAndAnimate(this, 'DNI <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">4. D.N.I.</button>
          <button onclick="copyTextAndAnimate(this, 'Constancia de CUIL <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">5. Constancia de cuil</button>
          <button onclick="copyTextAndAnimate(this, 'Negativa de ANSES <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">6. Negativa de ANSES</button>
          <button onclick="copyTextAndAnimate(this, 'Negativa de PUCO <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">7. Negativa de PUCO</button>
          <button onclick="copyTextAndAnimate(this, 'Negativa de S.S.Salud <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">8. Negativa de S.S.Salud</button>
          <button onclick="copyTextAndAnimate(this, 'Negativa PAMI <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">9. Negativa de PAMI</button>
          <button onclick="copyTextAndAnimate(this, 'Negativa IOMA <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">10. Negativa de IOMA</button>
          <button onclick="copyTextAndAnimate(this, 'História clínica <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">11. História clínica</button>
          <button onclick="copyTextAndAnimate(this, 'Foja quirurgica <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">12. Foja quirúrgica</button>
          <button onclick="copyTextAndAnimate(this, 'Estudios complementarios <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">13. Estudios complementarios</button>
          <button onclick="copyTextAndAnimate(this, 'Certificado de implante <?php echo $paciente ?>, <?php echo $dni ?>')" class="btn-verde copyButton">14. Certificado de implante</button>
        </div>
      </div>
    </div>

  </article>

  <script src="/Banco/public/js/tramitar.js"></script>
</body>

</html>