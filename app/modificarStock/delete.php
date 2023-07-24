<?php
session_start();

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

    // Consulta SQL para obtener el ID, el stock y el estado actual del item
    $query = "SELECT id, stock, estado FROM items WHERE id = :id";

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
            $currentStock = $result['stock'];

            // Verificar si el stock es igual a 0
            if ($currentStock === 0) {
                // Verificar si el estado actual es diferente a "del"
                if ($currentState !== 'del') {
                    // Consulta SQL para actualizar el estado a "del"
                    $updateQuery = "UPDATE items SET estado = 'del' WHERE id = :id";

                    // Preparar la sentencia de actualización
                    $updateStatement = $pdo->prepare($updateQuery);

                    // Bind del valor del ID para la actualización
                    $updateStatement->bindParam(':id', $id, PDO::PARAM_INT);

                    // Ejecutar la actualización
                    if ($updateStatement->execute()) {
                        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Item eliminado correctamente. Si esto fue un error, deberá comunicarse con un administrador absoluto.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
                        // Redireccionar a una página de éxito o mostrar un mensaje de éxito
                        header('Location: /Banco/public/layouts/modificarStock.php');
                        exit();
                    } else {
                        echo "Error al actualizar el estado.";
                    }
                } else {
                    echo "El estado ya es 'del'.";
                }
            } else {
                $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">El stock no es igual a 0, no se puede eliminar el item.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
                // Redireccionar a una página de error o mostrar un mensaje de error
                header('Location: /Banco/public/layouts/modificarStock.php');
                exit();
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
?>