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
    $banco = $_POST['banco'];
    $siglas = $_POST['siglas'];

    try {
        $stmt = $pdo->prepare("INSERT INTO bancos (banco, siglas) VALUES (:banco, :siglas)");
        $stmt->bindParam(':banco', $banco);
        $stmt->bindParam(':siglas', $siglas);
        $stmt->execute();

        // Redireccionar o mostrar un mensaje de éxito
        // ...
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Banco creado correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        header('Location: ../../public/layouts/profile.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}