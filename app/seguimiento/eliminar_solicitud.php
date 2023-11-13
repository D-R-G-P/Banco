<?php
// Requiere el archivo de la clase DB
require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

$user = new User();
$userSession = new UserSession();
$currentUser = $userSession->getCurrentUser();
$user->setUser($currentUser);

$db = new DB();
$pdo = $db->connect();

// Obtener el ID de la solicitud a eliminar
$solicitudId = $_POST['solicitudId'];

// Actualizar la intervención de la solicitud a 'deleted'
$updateQuery = "UPDATE solicitudes SET intervencion = 'deleted' WHERE id = :solicitudId";

try {
    // Preparar y ejecutar la actualización
    $statement = $pdo->prepare($updateQuery);
    $statement->bindParam(':solicitudId', $solicitudId, PDO::PARAM_INT);
    $statement->execute();

    // Actualización exitosa
    $_SESSION['success_message'] = 'Solicitud eliminada exitosamente';
} catch (PDOException $e) {
    // Error al ejecutar la actualización
    $_SESSION['error_message'] = 'Error al eliminar la solicitud: ' . $e->getMessage();
}

// Redirigir a la página inicial
header('Location: ../../public/layouts/getForm.php');
exit();
?>