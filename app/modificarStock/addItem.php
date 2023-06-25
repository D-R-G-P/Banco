<?php
require_once '../../app/db/db.php';

$db = new DB();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item'];
    $codigoBarras = $_POST['codigobarras'];
    $nombre = $_POST['nombre'];
    $descripcionCorta = $_POST['dcorta'];
    $descripcionLarga = $_POST['dlarga'];
    $estudios = $_POST['estudios'];
    $categoria = $_POST['categoria'];
    $banco = $_POST['banco'];

    try {
        $stmt = $pdo->prepare("INSERT INTO items (item, barcode, nombre, d_corta, d_larga, estudios, categoria, banco) VALUES (:item, :codigobarras, :nombre, :dcorta, :dlarga, :estudios, :categoria, :banco)");
        $stmt->bindParam(':item', $item);
        $stmt->bindParam(':codigobarras', $codigoBarras);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':dcorta', $descripcionCorta);
        $stmt->bindParam(':dlarga', $descripcionLarga);
        $stmt->bindParam(':estudios', $estudios);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':banco', $banco);
        $stmt->execute();

        // Redireccionar o mostrar un mensaje de éxito
        // ...
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Item añadido correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        header('Location: ../../public/layouts/modificarStock.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
