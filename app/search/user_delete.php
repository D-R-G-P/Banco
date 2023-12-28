<?php
require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

// Verificar si se ha enviado un ID válido a través de la URL
if (isset($_GET['userId']) && is_numeric($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Conexión a la base de datos
    $user = new User();
    $userSession = new UserSession();
    $currentUser = $userSession->getCurrentUser();
    $user->setUser($currentUser);

    $db = new DB();
    $pdo = $db->connect();

    // Consulta SQL para eliminar el usuario por ID
    $query = "DELETE FROM users WHERE id = :userId";

    // Preparar la sentencia
    $statement = $pdo->prepare($query);

    // Bind del valor del ID
    $statement->bindParam(':userId', $userId, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($statement->execute()) {
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Usuario eliminado correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        // Redireccionar a una página de éxito o mostrar un mensaje de éxito
        header('Location: /Banco/app/search/user.php');
        exit();
    } else {
        $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al eliminar el usuario: ' . implode(" ", $statement->errorInfo()) . '</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        // Redireccionar a una página de error o mostrar un mensaje de error
        header('Location: /Banco/app/search/user.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">El ID es inválido o no se proporcionó</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
    // Redireccionar a una página de error o mostrar un mensaje de error
    header('Location: /Banco/app/search/user.php');
    exit();
}
?>
