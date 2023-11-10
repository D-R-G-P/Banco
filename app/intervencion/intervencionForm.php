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

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $tipo_solicitud = $_POST['tipo_solicitud'];
    $fecha_solicitud = $_POST['fecha_solicitud'];
    $GDEBA = $_POST['banco'];
    $items_JSON = $_POST['jsonItems'];
    $paciente = $_POST['paciente'];
    $dni = $_POST['dni'];
    $estado = ''; // Puedes establecer un valor por defecto para el estado
    $tipo_cirugia = ''; // Puedes establecer un valor por defecto para el tipo de cirugía
    $fecha_perfeccionamiento = ''; // Puedes establecer un valor por defecto para la fecha de perfeccionamiento
    $sol_provision = ''; // Puedes establecer un valor por defecto para la solución de provisión
    $fecha_cirugia = ''; // Puedes establecer un valor por defecto para la fecha de cirugía
    $comentarios = ''; // Puedes establecer un valor por defecto para los comentarios



    // Verificar si hay errores al codificar la cadena JSON
    if (json_last_error() === JSON_ERROR_NONE) {
        // Crear la consulta de inserción
        $query = "INSERT INTO solicitudes (tipo_solicitud, fecha_solicitud, GDEBA, items_JSON, paciente, dni, estado, tipo_cirugia, fecha_perfeccionamiento, sol_provision, fecha_cirugia, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $pdo->prepare($query);

        // Ejecutar la consulta
        $stmt->execute([$tipo_solicitud, $fecha_solicitud, $GDEBA, $items_JSON, $paciente, $dni, $estado, $tipo_cirugia, $fecha_perfeccionamiento, $sol_provision, $fecha_cirugia, $comentarios]);

        // Mostrar un mensaje de éxito
        echo "Los datos se han insertado correctamente en la base de datos.";
    } else {
        // Mostrar un mensaje de error si hay errores en el formato JSON
        echo 'Error al codificar el JSON: ' . json_last_error_msg();
    }

    try {
        // Crear la consulta de inserción
        $query = "INSERT INTO solicitudes (tipo_solicitud, fecha_solicitud, GDEBA, items_JSON, paciente, dni, estado, tipo_cirugia, fecha_perfeccionamiento, sol_provision, fecha_cirugia, comentarios) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $pdo->prepare($query);

        // Ejecutar la consulta
        $stmt->execute([$tipo_solicitud, $fecha_solicitud, $GDEBA, $items_JSON, $paciente, $dni, $estado, $tipo_cirugia, $fecha_perfeccionamiento, $sol_provision, $fecha_cirugia, $comentarios]);

        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Paciente y material nominalizado correctamente.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';

        // Redirigir a otra página después de la inserción
        header("Location: ../../public/layouts/modificarStock.php");
        exit(); // Asegurar que no se ejecute nada más después de la redirección

    } catch (PDOException $e) {

        // Mostrar un mensaje de error en caso de que ocurra un error en la consulta
        $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al nominalizar. Vuelva a intentarlo o pongase en contacto con la administración.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        echo 'Error: ' . $e->getMessage();

        header("Location: ../../public/layouts/modificarStock.php");
        exit(); // Asegurar que no se ejecute nada más después de la redirección
        
    }
}
