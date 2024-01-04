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

if ($_POST["dato"] == 'inserta_archivo') {
    $referencia = uniqid();
    $titulo = $_POST["titulo"];
    $idUser = $_POST["idUser"];
    $matricula = $_POST["matricula"];

    if (!empty($_FILES["imagen"])) {
        $archivos = $_FILES["imagen"];
        $numArchivos = count($_FILES["imagen"]["tmp_name"]);

        // Aquí utilizamos la conexión PDO ($pdo) en lugar de $conexion
        $sql = "UPDATE banco.users SET ref = ?, firma = ?, matricula = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);

        for ($i = 0; $i < $numArchivos; $i++) {
            $tipoArchivo = mime_content_type($archivos["tmp_name"][$i]);

            // Lista de tipos MIME permitidos (puedes agregar más tipos si es necesario)
            $tiposPermitidos = ['image/jpeg', 'image/png'];

            if (in_array($tipoArchivo, $tiposPermitidos)) {
                $contenidoImagen = file_get_contents($archivos["tmp_name"][$i]);
                $imageBase64 = base64_encode($contenidoImagen);

                // Ejecutar la consulta pasando los parámetros directamente en execute
                if ($stmt->execute([$referencia, $imageBase64, $matricula, $idUser])) {
                    echo "Archivo " . ($i + 1) . " subido correctamente. <br>";
                } else {
                    echo "Error al subir el archivo " . ($i + 1) . ": " . $stmt->errorInfo()[2] . "<br>";
                }
            } else {
                echo "Tipo de archivo no permitido para el archivo " . ($i + 1) . ". <br>";
            }
        }
        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Firma registrada correctamente</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        header('Location: user.php');
    } else {
        echo 'No se han seleccionado archivos para subir.';
    }
}
