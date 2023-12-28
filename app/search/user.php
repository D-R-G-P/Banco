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
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/search/modificarUser?id=' . $id . '" title="Modificar usuario"><i class="fa-solid fa-pencil"></i></a>
                        <button class="btn-verde actionButton" style="font-size: 1.3vw;" onclick="dialogoArchivo(\'' . $id . '\', \'' . $nombre . '\', \'' . $apellido . '\', \'' . $dni . '\', \'' . $username . '\')" title="Eliminar usuario"><i class="fa-solid fa-trash"></i></button>
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

    <div class="fondo" id="fondoArchive" style="display: none;">
        <div class="archive">
            <h3 style="font-size: 1.5vw; margin-top: 1.5vw;">¿Está seguro que desea borrar este usuario?</h3>

            <div class="datosExpediente" style="margin: 1.5vw 0;">
                <p style="font-size: 1.2vw;"><b>Nombre de usuario:</b> <span id="borrarUsername"></span></p>
                <p style="font-size: 1.2vw;"><b>Nombre:</b> <span id="borrarNombre"></span></p>
                <p style="font-size: 1.2vw;"><b>D.N.I:</b> <span id="borrarDni"></span></p>
            </div>

            <div class="botonesArchi">
                <button class="btn-verde" onclick="cerrarDialogoArchivo()"><i class="fa-solid fa-x"></i> Cancelar</button>
                <a style="text-decoration: none; text-align: center;" class="btn-rojo" href="#" id="borrarBTN"><i class="fa-solid fa-trash"></i> Borrar usuario</a>
            </div>
        </div>
    </div>
</article>



<script src="/Banco/public/js/user.js"></script>

<?php include_once "../../public/layouts/bases/footer.php"; ?>