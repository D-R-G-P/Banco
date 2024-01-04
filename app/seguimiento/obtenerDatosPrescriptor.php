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

// Obtener la matrícula del firmante desde la solicitud AJAX
$matricula = $_GET['firmante'];

// Realizar la consulta para obtener los detalles del prescriptor
// (Asegúrate de preparar la consulta para evitar inyecciones SQL)
$query = "SELECT nombre, apellido, matricula FROM users WHERE matricula = :matricula";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':matricula', $matricula, PDO::PARAM_INT);
$stmt->execute();

// Obtener los resultados y devolverlos en formato HTML
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Nombre completo: " . $row['apellido'] . " " . $row['nombre'] . "<br>Matricula: " . $row['matricula'];
} else {
    echo "No se encontraron datos para la matrícula proporcionada.";
}
?>