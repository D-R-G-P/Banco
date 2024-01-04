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

    if (!empty($_FILES["imagen"]["tmp_name"])) {
        $archivos = $_FILES["imagen"];
        $numArchivos = count($archivos["tmp_name"]);

        $sql = "INSERT INTO test.test2 (ref, titulo, archivo) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);

        for ($i = 0; $i < $numArchivos; $i++) {
            $tipoArchivo = mime_content_type($archivos["tmp_name"][$i]);

            // Lista de tipos MIME permitidos (puedes agregar más tipos si es necesario)
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];

            if (in_array($tipoArchivo, $tiposPermitidos)) {
                $contenidoImagen = file_get_contents($archivos["tmp_name"][$i]);
                $imageBase64 = base64_encode($contenidoImagen);

                $stmt->bind_param("sss", $referencia, $titulo, $imageBase64);

                if ($stmt->execute()) {
                    echo "Archivo " . ($i + 1) . " subido correctamente. <br>";
                } else {
                    echo "Error al subir el archivo " . ($i + 1) . ": " . $stmt->error . "<br>";
                }
            } else {
                echo "Tipo de archivo no permitido para el archivo " . ($i + 1) . ". <br>";
            }
        }
    } else {
        echo 'No se han seleccionado archivos para subir.';
    }
}
