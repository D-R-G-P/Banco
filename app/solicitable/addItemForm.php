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

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario

    $banco = $_POST['banco'];
    $item = $_POST['item'];
    $descripcion = $_POST['descripcion'];
    $descripcionAmpliada = $_POST['descripcionAmpliada'];
    $estPre = $_POST['estPre'];
    $estPos = $_POST['estPos'];
    $estado = "act";

    // Realizar la actualización en la base de datos
    try {
        // Construir la consulta SQL de actualización
        $query = "INSERT INTO itemssolicitables SET banco = :banco, item = :item, descripcion = :descripcion, descripcionAmpliada = :descripcionAmpliada, estPre = :estPre, estPos = :estPos, estado = :estado";

        // Preparar la sentencia
        $stmt = $pdo->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(':banco', $banco);
        $stmt->bindParam(':item', $item);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':descripcionAmpliada', $descripcionAmpliada);
        $stmt->bindParam(':estPre', $estPre);
        $stmt->bindParam(':estPos', $estPos);
        $stmt->bindParam(':estado', $estado);

        // Ejecutar la actualización
        $stmt->execute();

        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Datos modificados correctamente.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';

        // Redirigir a una página de éxito o realizar alguna otra acción
        header('Location: ../../public/layouts/realizarPedido.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al procesar los datos. Vuelva a intentarlo o póngase en contacto con la administración.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        // Manejar errores de la base de datos
        echo 'Error al actualizar la base de datos: ' . $e->getMessage();
    }
}