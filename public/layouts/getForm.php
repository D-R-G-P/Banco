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

$titulo_pestaña = "Nuevo caso";

?>

<?php include_once 'bases/header.php'; ?>

<link rel="stylesheet" href="/Banco/public/css/table.css">

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
                        if ($solicitud['tipo_solicitud'] == "Para nominalizar stock") {
                            // Definir la consulta SQL para obtener los items
                            $itemsQuery = "SELECT `id`, `item`, `barcode`, `nombre`, `d_corta`, `d_larga`, `estudios`, `stock`, `categoria`, `banco`, `estado` FROM `items`";

                            // Preparar y ejecutar la consulta para obtener los items
                            $itemsStatement = $pdo->prepare($itemsQuery);
                            $itemsStatement->execute();
                            $itemsData = $itemsStatement->fetchAll(PDO::FETCH_ASSOC);
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
                                echo "<p><b>Item:</b> $numItem, $nombreItem -> <b>Cantidad:</b> $cantidad</p><br>";
                            }
                        } elseif ($solicitud['tipo_solicitud'] == "Para cirugía") {
                            // Definir la consulta SQL para obtener los items
                            $itemsQuery = "SELECT `id`, `item`, `descripcion`, `descripcionAmpliada` FROM `itemssolicitables`";

                            // Preparar y ejecutar la consulta para obtener los items
                            $itemsStatement = $pdo->prepare($itemsQuery);
                            $itemsStatement->execute();
                            $itemsData = $itemsStatement->fetchAll(PDO::FETCH_ASSOC);
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
                                        $descripcion = $itemData['descripcion'];
                                        $descripcionAmpliada = $itemData['descripcionAmpliada'];
                                        break;
                                    }
                                }

                                // Mostrar el nombre del item y la cantidad
                                echo "<p><b>Item:</b> $numItem, $descripcion, $descripcionAmpliada -> <b>Cantidad:</b> $cantidad</p><br>";
                            }
                        }
                        ?>
                    </td>
                    <td><?= $solicitud['banco'] ?></td>
                    <td style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-evenly; align-items: center;">
                        <a class="btn-verde" href="/Banco/app/seguimiento/tramitar.php?idSol=<?= $solicitud['id'] ?>"><i class="fa-solid fa-hand-pointer"></i></a>
                        <a class="btn-verde" href="/Banco/app/seguimiento/eliminar_solicitud.php?solicitudId=<?= $solicitud['id'] ?>"><i class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</article>

<?php include_once 'bases/footer.php'; ?>

</html>