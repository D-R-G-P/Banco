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
    <title>S.C.S. - Modificar stock</title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/table.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
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

    <header>
        <div class="logo">
            <a href="/Banco/"><i class="fa-solid fa-dolly"></i></a>
        </div>

        <div class="links">
            <a href="/Banco/">Inicio</a>
            <a href="/Banco/public/layouts/modificarStock">Modificar stock</a>
            <a href="/Banco/public/layouts/seguimientoSolicitudes" class="disabled">Seguimiento</a>
            <a href="/Banco/public/layouts/realizarPedido" class="disabled">Realizar pedido</a>
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
                <a class="profile" href="/Banco/public/layouts/profile">Ir a mi perfil</a>
                <a style="color: red;" href="/Banco/app/db/logout"><i class="fa-solid fa-power-off"></i> Cerrar
                    sesión</a>
            </div>
        </div>
    </header>
</body>

<article>
    <table>
        <thead>
            <th>Tipo de solicitud</th>
            <th>Fecha de solicitud</th>
            <th>Paciente</th>
            <th>DNI</th>
            <th>Items</th>
            <th>Banco</th>
            <th>Acciones</th>
        </thead>
        <tbody>

            <?php
            // Definir la consulta SQL para obtener los items
            $itemsQuery = "SELECT `id`, `item`, `barcode`, `nombre`, `d_corta`, `d_larga`, `estudios`, `stock`, `categoria`, `banco`, `estado` FROM `items`";

            // Preparar y ejecutar la consulta para obtener los items
            $itemsStatement = $pdo->prepare($itemsQuery);
            $itemsStatement->execute();
            $itemsData = $itemsStatement->fetchAll(PDO::FETCH_ASSOC);

            // Definir la consulta SQL para obtener las solicitudes
            $solicitudesQuery = "SELECT id, tipo_solicitud, DATE_FORMAT(fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud, items_JSON, paciente, dni, banco, intervencion FROM solicitudes WHERE intervencion = 'no' ORDER BY id ASC";

            // Preparar y ejecutar la consulta para obtener las solicitudes
            $solicitudesStatement = $pdo->prepare($solicitudesQuery);
            $solicitudesStatement->execute();
            $solicitudes = $solicitudesStatement->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php foreach ($solicitudes as $solicitud) : ?>
                <tr>
                    <td><?= $solicitud['tipo_solicitud'] ?></td>
                    <td><?= $solicitud['fecha_solicitud'] ?></td>
                    <td><?= $solicitud['paciente'] ?></td>
                    <td><?= $solicitud['dni'] ?></td>
                    <td>
                        <?php
                        // Decodificar el JSON de items
                        $itemsJson = json_decode($solicitud['items_JSON'], true);

                        // Traducir y mostrar los items
                        foreach ($itemsJson as $itemJson) {
                            $itemId = $itemJson['id'];
                            $cantidad = $itemJson['cantidad'];

                            // Buscar el nombre del item en la tabla de items
                            $nombreItem = '';
                            foreach ($itemsData as $itemData) {
                                if ($itemData['id'] == $itemId) {
                                    $numItem = $itemData['item'];
                                    $nombreItem = $itemData['nombre'];
                                    break;
                                }
                            }

                            // Mostrar el nombre del item y la cantidad
                            echo "<b>Item:</b> $numItem, $nombreItem -> <b>Cantidad:</b> $cantidad<br>";
                        }
                        ?>
                    </td>
                    <td><?= $solicitud['banco'] ?></td>
                    <td style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-evenly; align-items: center;">
                        <button class="btn-verde"><i class="fa-solid fa-hand-pointer"></i></button>
                        <a class="btn-verde" href="/Banco/app/seguimiento/eliminar_solicitud.php?solicitudId=<?= $solicitud['id'] ?>"><i class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</article>

<script src="/Banco/public/js/header.js"></script>

</html>