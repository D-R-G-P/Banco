<?php
require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

// Verificar si se ha enviado un ID válido a través de la URL
if (isset($_GET['solicitudId']) && is_numeric($_GET['solicitudId'])) {
    $solicitudId = $_GET['solicitudId'];

    // Conexión a la base de datos
    $user = new User();
    $userSession = new UserSession();
    $currentUser = $userSession->getCurrentUser();
    $user->setUser($currentUser);

    $db = new DB();
    $pdo = $db->connect();

    // Consulta SQL para obtener el ID y el estado actual del item
    $query = "SELECT id, intervencion FROM solicitudes WHERE id = :solicitudId";

    // Preparar la sentencia
    $statement = $pdo->prepare($query);

    // Bind del valor del ID
    $statement->bindParam(':solicitudId', $solicitudId, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($statement->execute()) {
        // Obtener el resultado de la consulta
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $currentState = $result['intervencion'];

            // Verificar si el estado actual es diferente a "act"
            if ($currentState == 'no') {
                // Consulta SQL para actualizar el estado a "act"
                $updateQuery = "UPDATE solicitudes SET intervencion = 'deleted' WHERE id = :solicitudId";

                // Preparar la sentencia de actualización
                $updateStatement = $pdo->prepare($updateQuery);

                // Bind del valor del ID para la actualización
                $updateStatement->bindParam(':solicitudId', $solicitudId, PDO::PARAM_INT);

                // Ejecutar la actualización
                if ($updateStatement->execute()) {
                    $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Solicitud anulada correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
                    // Redireccionar a una página de éxito o mostrar un mensaje de éxito
                    header('Location: /Banco/public/layouts/getForm.php');
                    exit();
                } else {
                    $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al actualizar el estado: ' . implode(" ", $updateStatement->errorInfo()) . '</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
                    // Redireccionar a una página de error o mostrar un mensaje de error
                    header('Location: /Banco/public/layouts/getForm.php');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">La solicitud ya está anulada</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
                // Redireccionar a una página de error o mostrar un mensaje de error
                header('Location: /Banco/public/layouts/getForm.php');
                exit();
            }
        } else {
            $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">No se encontró el ID en la base de datos</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
            // Redireccionar a una página de error o mostrar un mensaje de error
            header('Location: /Banco/public/layouts/getForm.php');
            exit();
        }
    } else {
        echo "Error al ejecutar la consulta: " . implode(" ", $statement->errorInfo());

        $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al ejecutar la consulta: ' . implode(" ", $statement->errorInfo()) . '</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        // Redireccionar a una página de error o mostrar un mensaje de error
        header('Location: /Banco/public/layouts/getForm.php');
        exit();
    }
} else {
    $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">El ID es invalido o no se proporcionó</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
    // Redireccionar a una página de error o mostrar un mensaje de error
    header('Location: /Banco/public/layouts/getForm.php');
    exit();
}
