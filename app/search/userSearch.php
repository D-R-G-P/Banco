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
$stmt = $pdo->prepare("SELECT * FROM users WHERE dni = :dni");
$stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
$stmt->execute();

// Construir la tabla con los resultados
echo '<table>';
echo '<thead>';
echo '<tr>';
echo '<th style="text-align: center; vertical-align: middle;">Id</th>';
echo '<th>Nombre</th>';
echo '<th>Apellido</th>';
echo '<th>DNI</th>';
echo '<th>Username</th>';
echo '<th style="text-align: center; vertical-align: middle;">Cargo</th>';
echo '<th style="text-align: center; vertical-align: middle;">Tipo de usuario</th>';
echo '<th style="text-align: center; vertical-align: middle;">Banco</th>';
echo '<th style="text-align: center; vertical-align: middle;">Acciones</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id'];
    $nombre = $row['nombre'];
    $apellido = $row['apellido'];
    $dni = $row['dni'];
    $username = $row['username'];
    $cargo = $row['cargo'];
    $tipo_usuario = $row['tipo_usuario'];
    $banco = $row['banco'];

    echo '<tr>';
    echo '<td style="text-align: center; vertical-align: middle;">' . $id . '</td>';
    echo '<td style="vertical-align: middle;">' . $nombre . '</td>';
    echo '<td style="vertical-align: middle;">' . $apellido . '</td>';
    echo '<td style="vertical-align: middle;">' . $dni . '</td>';
    echo '<td style="vertical-align: middle;">' . $username . '</td>';
    echo '<td style="text-align: center; vertical-align: middle;">' . $cargo . '</td>';
    echo '<td style="text-align: center; vertical-align: middle;">' . $tipo_usuario . '</td>';
    echo '<td style="text-align: center; vertical-align: middle;">' . $banco . '</td>';
    echo '<td style="vertical-align: middle; width: 5.5vw; text-align-last: justify;">
    <a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/search/modificarUser?id=' . $id . '" title="Modificar usuario"><i class="fa-solid fa-pencil"></i></a>
    <button class="btn-verde actionButton" style="font-size: 1.3vw;" onclick="dialogoArchivo(\'' . $id . '\', \'' . $nombre . '\', \'' . $apellido . '\', \'' . $dni . '\', \'' . $username . '\')" title="Eliminar usuario"><i class="fa-solid fa-trash"></i></button>
							</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
