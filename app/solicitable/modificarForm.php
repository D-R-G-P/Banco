<?php
require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

$user = new User();
$userSession = new UserSession();
$currentUser = $userSession->getCurrentUser();
$user->setUser($currentUser);

$username = $user->getUsername();
$pass = $user->getPassword();

$db = new DB();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idItem = $_POST["idItem"];
    $banco = $_POST['banco'];
    $item = $_POST['item'];
    $descripcion = $_POST['descripcion'];
    $descripcionAmpliada = $_POST['descripcionAmpliada'];
    $estPre = $_POST['estPre'];
    $estPos = $_POST['estPos'];

    try {
        $stmt = $pdo->prepare("UPDATE itemssolicitables SET banco = :banco, item = :item, descripcion = :descripcion, descripcionAmpliada = :descripcionAmpliada, estPre = :estPre, estPos = :estPos WHERE id = :idItem");
        $stmt->bindParam(':idItem', $idItem); // Agrega esta línea para vincular el valor de :idUser
        $stmt->bindParam(':banco', $banco);
        $stmt->bindParam(':item', $item);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':descripcionAmpliada', $descripcionAmpliada);
        $stmt->bindParam(':estPre', $estPre);
        $stmt->bindParam(':estPos', $estPos);
        $stmt->execute();


        // Redireccionar o mostrar un mensaje de éxito
        // ...
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Datos modificados correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        header('Location: ../../public/layouts/realizarPedido.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}