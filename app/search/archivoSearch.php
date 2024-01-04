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

// Obtener el valor del DNI enviado por AJAX
$dni = isset($_POST['dni']) ? $_POST['dni'] : '';

// Consulta SQL para buscar por DNI
$stmt = $pdo->prepare("SELECT * FROM solicitudes WHERE dni = :dni");
$stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
$stmt->execute();

// Construir la tabla con los resultados
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
                        <a class="btn-verde actionButton" style="font-size: 1.3vw;" title="Desarchivar expediente" href="/Banco/app/search/desarchivarSol.php?solicitudId=' . $id . '"><i class="fa-solid fa-boxes-packing"></i></a>
							</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
