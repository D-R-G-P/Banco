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

// Verificar si se ha proporcionado la variable siglas
if (isset($_GET['siglas'])) {
    $siglas = $_GET['siglas'];

    // Consulta para obtener las categorías relacionadas con las siglas del banco
    $stmt = $pdo->prepare("SELECT DISTINCT categoria FROM categorias WHERE banco = :siglas");
    $stmt->bindParam(':siglas', $siglas);
    $stmt->execute();

    $resultados = array();

    // Obtener los resultados de la consulta
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resultados[] = $row['categoria'];
    }

    // Devolver los resultados como respuesta en formato JSON
    echo json_encode($resultados);
}
?>