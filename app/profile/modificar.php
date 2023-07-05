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
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $password = $_POST['password'];
    $cargo = $_POST['cargo'];

    // Verificar si se ingresó una nueva contraseña
    if (!empty($password)) {
        $password = md5($password); // Guardar la contraseña como MD5
    } else {
        $password = $pass; // Mantener la contraseña actual
    }

    try {
        $stmt = $pdo->prepare("UPDATE users SET nombre = :nombre, apellido = :apellido, dni = :dni, password = :password WHERE username = :username");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Redireccionar o mostrar un mensaje de éxito
        // ...
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Datos modificados correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        header('Location: ../../public/layouts/profile.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
