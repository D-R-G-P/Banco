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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $barcode = $_POST['codebar'];
    $lote = $_POST['lote'];
    $cantidad = "+" . $_POST['stock'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del formulario
        $barcode = $_POST['codebar'];
        $lote = $_POST['lote'];
        $cantidad = +(int)$_POST['stock']; // Convertir a entero y negar el valor

        try {
            // Restar el stock en la tabla items
            $stmt = $pdo->prepare("UPDATE items SET stock = stock + :cantidad WHERE barcode = :barcode");
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->bindParam(':barcode', $barcode);
            $stmt->execute();

            // Insertar los datos en la tabla stock
            $stmt = $pdo->prepare("INSERT INTO stock (barcode, lote, cantidad) VALUES (:barcode, :lote, :cantidad)");
            $stmt->bindParam(':barcode', $barcode);
            $stmt->bindParam(':lote', $lote);
            $stmt->bindParam(':cantidad', $cantidad);
            $stmt->execute();

            // Redireccionar o mostrar un mensaje de éxito
            // ...
            $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Stock añadido correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
            header('Location: ../../public/layouts/modificarStock.php');
            exit();
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
