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

$titulo_pestaña = "Buscar usuario";

?>

<?php include_once "../../public/layouts/bases/header.php"; ?>

<link rel="stylesheet" href="/Banco/public/css/user.css">
<link rel="stylesheet" href="/Banco/public/css/table.css">

<div class="busqueda">
    <label for="campoBusqueda">Buscar usuario:</label>
    <input id="campoBusqueda" name="campoBusqueda" type="number" placeholder="Buscar por DNI">
    <button class="btn-verde" id="btnBuscar"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
    <hr>
</div>

<article>
    <?php
    try {
        $stmt = $pdo->prepare("SELECT i.id, i.nombre, i.apellido, i.dni, i.username, i.cargo, i.tipo_usuario, i.banco FROM users i ORDER BY i.id ASC");
        $stmt->execute();

        echo '<table id="tablaBase">';
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
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/modificar?id=' . $id . '" title="Modificar stock de este item"><i class="fa-solid fa-pencil"></i></a>
                        <a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/delete?id=' . $id . '" title="Eliminar item (no deberá haber stock disponible)"><i class="fa-solid fa-trash"></i></a>
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



<script src="/Banco/public/js/user.js"></script>

<?php include_once "../../public/layouts/bases/footer.php"; ?>