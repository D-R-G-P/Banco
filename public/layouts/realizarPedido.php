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

$titulo_pestaña = "Realizar pedido";

?>

<?php include_once 'bases/header.php'; ?>
<link rel="stylesheet" href="/Banco/public/css/realizarPedido.css">

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


<article style="padding: 1vw">

    <div class="tabla">
        <h1>Items activos</h1>
        <!-- style="text-align: center; vertical-align: middle;" -->

        <?php
        try {
            $stmt = $pdo->prepare("SELECT i.id, i.banco, i.item, i.descripcion, i.descripcionAmpliada, i.estPre, i.estPos, i.estado
            FROM itemssolicitables AS i
            INNER JOIN bancos AS b ON i.banco = b.banco
            ORDER BY i.item;");
            $stmt->execute();

            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Banco</th>';
            echo '<th>Item</th>';
            echo '<th>Descripción</th>';
            echo '<th>Descripción ampliada</th>';
            echo '<th>Estudios prequirurgicos</th>';
            echo '<th>Estudios post quirurgicos</th>';
            echo '<th>Acciones</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $item = $row['item'];
                $nombre = $row['nombre'];
                $d_corta = $row['d_corta'];
                $d_larga = $row['d_larga'];
                $estudios = $row['estudios'];
                $stock = $row['stock'];
                $categoria = $row['categoria'];
                $banco = $row['banco'];
                $estado = $row['estado'];

                echo '<tr>';
                echo '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
                echo '<td style="vertical-align: middle;">' . $nombre . '</td>';
                echo '<td style="vertical-align: middle;">' . $d_corta . '</td>';
                echo '<td style="vertical-align: middle;">' . $d_larga . '</td>';
                echo '<td style="vertical-align: middle;">' . $estudios . '</td>';
                echo '<td style="text-align: center; vertical-align: middle;">' . $stock . '</td>';
                if ($estado == "act") {
                    echo '<td style="vertical-align: middle; width: 8vw; text-align-last: justify;">
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/disable?id=' . $id . '" title="Deshabilitar item"><i class="fa-regular fa-circle-check"></i></i></a>
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/delete?id=' . $id . '" title="Eliminar item (no deberá haber stock disponible)"><i class="fa-solid fa-trash"></i></a>
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/modificar?id=' . $id . '" title="Modificar stock de este item"><i class="fa-solid fa-pencil"></i></a>
							</td>';
                } else if ($estado == "des") {
                    echo '<td style="vertical-align: middle; width: 8vw; text-align-last: justify;">
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/enable?id=' . $id . '" title="Habilitar item"><i class="fa-regular fa-circle-xmark"></i></a>
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/delete?id=' . $id . '" title="Eliminar item (no deberá haber stock disponible)"><i class="fa-solid fa-trash"></i></a>
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/modificar?id=' . $id . '" title="Modificar stock de este item"><i class="fa-solid fa-pencil"></i></a>
					</td>';
                }
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        ?>
    </div>

</article>



<?php include_once 'bases/footer.php'; ?>

</body>

<script src="/Banco/public/js/realizarPedido.js"></script>

</html>