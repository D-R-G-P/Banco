<?php

// Verificar si se ha enviado un ID válido a través de la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Conexión a la base de datos
    require_once '../../app/db/user_session.php';
    require_once '../../app/db/user.php';
    require_once '../../app/db/db.php';

    $user = new User();
    $userSession = new UserSession();
    $currentUser = $userSession->getCurrentUser();
    $user->setUser($currentUser);

    $db = new DB();
    $pdo = $db->connect();

    // Consulta SQL para obtener el ID y el estado actual del item
    $query = "SELECT id, estado FROM items WHERE id = :id";

    // Preparar la sentencia
    $statement = $pdo->prepare($query);

    // Bind del valor del ID
    $statement->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($statement->execute()) {
        // Obtener el resultado de la consulta
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $currentState = $result['estado'];

            // Verificar si el estado actual es diferente a "des"
            if ($currentState !== 'des') {
                // Consulta SQL para actualizar el estado a "des"
                $updateQuery = "UPDATE items SET estado = 'des' WHERE id = :id";

                // Preparar la sentencia de actualización
                $updateStatement = $pdo->prepare($updateQuery);

                // Bind del valor del ID para la actualización
                $updateStatement->bindParam(':id', $id, PDO::PARAM_INT);

                // Ejecutar la actualización
                if ($updateStatement->execute()) {
                    $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Item deshabilitado correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
                    // Redireccionar a una página de éxito o mostrar un mensaje de éxito
                    header('Location: /Banco/public/layouts/modificarStock.php');
                    exit();
                } else {
                    echo "Error al actualizar el estado.";
                }
            } else {
                echo "El estado ya es 'des'.";
            }
        } else {
            echo "No se encontró el ID en la base de datos.";
        }
    } else {
        echo "Error al ejecutar la consulta.";
    }
} else {
    echo "ID no válido o no se proporcionó.";
}