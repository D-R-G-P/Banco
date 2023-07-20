<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verificar si todos los campos del formulario están presentes
    if (
        isset($_POST["paciente"], $_POST["edad"], $_POST["tipoDocumento"], $_POST["documento"], $_POST["direccion"], $_POST["telefono"], $_POST["controlBuscador"], $_POST["controlBuscadorSecond"], $_POST["items_json"])
        && !empty($_POST["paciente"])
        && !empty($_POST["edad"])
        && !empty($_POST["tipoDocumento"])
        && !empty($_POST["documento"])
        && !empty($_POST["direccion"])
        && !empty($_POST["telefono"])
        && !empty($_POST["controlBuscador"])
        && !empty($_POST["controlBuscadorSecond"])
        && !empty($_POST["items_json"])
    ) {
        // Obtener los valores del formulario
        $paciente = $_POST["paciente"];
        $edad = $_POST["edad"];
        $tipoDocumento = $_POST["tipoDocumento"];
        $documento = $_POST["documento"];
        $direccion = $_POST["direccion"];
        $telefono = $_POST["telefono"];
        $diagnostico = $_POST["controlBuscador"];
        $nomenclador_cirugia = $_POST["controlBuscadorSecond"];
        $items_json = $_POST["items_json"];

        // Conexión a la base de datos (Utilizando la clase DB que proporcionaste)
        require_once "../../db/db.php"; // Reemplaza "ruta_donde_se_encuentra_la_clase_DB.php" con la ruta correcta del archivo que contiene la clase DB
        $db = new DB();
        $pdo = $db->connect();

        // Preparar la consulta SQL para insertar los datos
        $query = "INSERT INTO cigeforms (nombre_apellido, edad, tipo_documento, documento, domicilio, telefono, diagnostico, nomenclador_cirugia, items_json) VALUES (:nombre_apellido, :edad, :tipo_documento, :documento, :domicilio, :telefono, :diagnostico, :nomenclador_cirugia, :items_json)";

        // Preparar la sentencia
        $statement = $pdo->prepare($query);

        // Bind de los valores
        $statement->bindParam(":nombre_apellido", $paciente);
        $statement->bindParam(":edad", $edad);
        $statement->bindParam(":tipo_documento", $tipoDocumento);
        $statement->bindParam(":documento", $documento);
        $statement->bindParam(":domicilio", $direccion);
        $statement->bindParam(":telefono", $telefono);
        $statement->bindParam(":diagnostico", $diagnostico);
        $statement->bindParam(":nomenclador_cirugia", $nomenclador_cirugia);
        $statement->bindParam(":items_json", $items_json);

        // Ejecutar la consulta
        if ($statement->execute()) {
            // Redireccionar a una página de éxito o mostrar un mensaje de éxito
            header("Location: ../../../public/layouts/realizarPedido.php");
            exit();
        } else {
            // Mostrar un mensaje de error en caso de fallo
            echo "Error al registrar los datos en la base de datos.";
        }
    } else {
        echo "Por favor, complete todos los campos del formulario.";
    }
} else {
    echo "Método de solicitud no válido.";
}
