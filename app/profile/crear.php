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
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Guardar la contraseña como MD5
    $cargo = $_POST['cargo'];
    $banco = $_POST['banco'];
    $tipoUsuario = $_POST['tipo_usuario'];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (nombre, apellido, dni, username, password, cargo, banco, tipo_usuario) VALUES (:nombre, :apellido, :dni, :username, :password, :cargo, :banco, :tipoUsuario)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':banco', $banco);
        $stmt->bindParam(':tipoUsuario', $tipoUsuario);
        $stmt->execute();

        // Redireccionar o mostrar un mensaje de éxito
        // ...
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Usuario creado correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        header('Location: ../../public/layouts/profile.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}