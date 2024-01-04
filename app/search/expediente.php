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

$titulo_pestaña = "Buscar expediente";

?>

<?php include_once "../../public/layouts/bases/header.php"; ?>

<link rel="stylesheet" href="/Banco/public/css/user.css">
<link rel="stylesheet" href="/Banco/public/css/table.css">

<div class="busqueda">
    <label for="campoBusqueda">Buscar persona:</label>
    <input id="campoBusqueda" name="campoBusqueda" type="text" placeholder="Buscar por DNI" oninput="formatDNI(this)">
    <button class="btn-verde" id="btnBuscar"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
    <hr>
</div>

<article>
    <?php
    try {
        $stmt = $pdo->prepare("SELECT i.id, i.solicitud, i.tipo_solicitud, DATE_FORMAT(i.fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud, i.GDEBA, i.paciente, i.dni, i.estado, i.tipo_cirugia FROM solicitudes i WHERE i.intervencion = 'archivo' ORDER BY i.id ASC");

        $stmt->execute();

        echo '<table id="tablaBase">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="text-align: center; vertical-align: middle;">Solicitud</th>';
        echo '<th>Tipo de solicitud</th>';
        echo '<th>Fecha de solicitud</th>';
        echo '<th>GDEBA</th>';
        echo '<th>Paciente</th>';
        echo '<th style="text-align: center; vertical-align: middle;">DNI</th>';
        echo '<th style="text-align: center; vertical-align: middle;">Estado</th>';
        echo '<th style="text-align: center; vertical-align: middle;">Tipo de cirugía</th>';
        echo '<th style="text-align: center; vertical-align: middle;">Acciones</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $solicitud = $row['solicitud'];
            $tipo_solicitud = $row['tipo_solicitud'];
            $fecha_solicitud = $row['fecha_solicitud'];
            $GDEBA = $row['GDEBA'];
            $paciente = $row['paciente'];
            $dni = $row['dni'];
            $estado = $row['estado'];
            $tipo_cirugia = $row['tipo_cirugia'];

            echo '<tr>';
            echo '<td style="text-align: center; vertical-align: middle;">' . $solicitud . '</td>';
            echo '<td style="vertical-align: middle;">' . $tipo_solicitud . '</td>';
            echo '<td style="vertical-align: middle;">' . $fecha_solicitud . '</td>';
            echo '<td style="vertical-align: middle;">' . $GDEBA . '</td>';
            echo '<td style="vertical-align: middle;">' . $paciente . '</td>';
            echo '<td style="text-align: center; vertical-align: middle;">' . $dni . '</td>';
            echo '<td style="text-align: center; vertical-align: middle;">' . $estado . '</td>';
            echo '<td style="text-align: center; vertical-align: middle;">' . $tipo_cirugia . '</td>';
            echo '<td style="vertical-align: middle; width: 6vw; text-align-last: justify;">
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/seguimiento/tramitar?idSol=' . $id . '" title="Ingresar a expediente"><i class="fa-solid fa-hand-pointer"></i></a>
                        <a class="btn-verde actionButton" style="font-size: 1.3vw;" title="Desarchivar expediente" href="/Banco/app/search/desarchivarSol.php?solicitudId='.$id.'"><i class="fa-solid fa-boxes-packing"></i></a>
							</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>
    <div id="resultadoTabla">
        <!-- Tabla de resultados se mostrará aquí -->
    </div>
</article>



<script src="/Banco/public/js/archivo.js"></script>

<?php include_once "../../public/layouts/bases/footer.php"; ?>