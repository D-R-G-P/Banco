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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $fecha_solicitud = $_POST['fecha_solicitud'];
    $paciente = $_POST['paciente'];
    $dni = $_POST['dni'];
    $solicitud = $_POST['solicitud'];
    $tipo_solicitud = $_POST['tipo_solicitud'];
    $tipo_cirugia = $_POST['tipo_cirugia'];
    $fecha_perfeccionamiento = $_POST['fecha_perfeccionamiento'];
    $sol_provision = $_POST['sol_provision'];
    $fecha_cirugia = $_POST['fecha_cirugia'];
    $GDEBA = $_POST['GDEBA'];
    $estado = $_POST['estado'];
    $comentarios = $_POST['comentarios'];
    $nomencladores = $_POST['nomencladores'];
    $categoriascie = $_POST['categoriascie'];
    $domicilio = $_POST['domicilio'];
    $localidad = $_POST['localidad'];
    $sexo = $_POST['sexo'];
    $edad = $_POST['edad'];
    $tipoDoc = $_POST['tipoDoc'];
    $telefono = $_POST['telefono'];
    $firmante = $_POST['firmante'];
    $id = $_POST['id'];

    // Realizar la actualización en la base de datos
    try {
        // Construir la consulta SQL de actualización
        $query = "UPDATE solicitudes SET fecha_solicitud = :fecha_solicitud, paciente = :paciente, dni = :dni, solicitud = :solicitud, tipo_solicitud = :tipo_solicitud, tipo_cirugia = :tipo_cirugia, fecha_perfeccionamiento = :fecha_perfeccionamiento, sol_provision = :sol_provision, fecha_cirugia = :fecha_cirugia, GDEBA = :GDEBA, estado = :estado, comentarios = :comentarios, nomencladores = :nomencladores, categoriascie = :categoriascie, domicilio = :domicilio, localidad = :localidad, sexo = :sexo, edad = :edad, tipoDoc = :tipoDoc, telefono = :telefono, firmante = :firmante WHERE id = :id";

        // Preparar la sentencia
        $stmt = $pdo->prepare($query);

        // Bind de los parámetros
        $stmt->bindParam(':fecha_solicitud', $fecha_solicitud);
        $stmt->bindParam(':paciente', $paciente);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':solicitud', $solicitud);
        $stmt->bindParam(':tipo_solicitud', $tipo_solicitud);
        $stmt->bindParam(':tipo_cirugia', $tipo_cirugia);
        $stmt->bindParam(':fecha_perfeccionamiento', $fecha_perfeccionamiento);
        $stmt->bindParam(':sol_provision', $sol_provision);
        $stmt->bindParam(':fecha_cirugia', $fecha_cirugia);
        $stmt->bindParam(':GDEBA', $GDEBA);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':comentarios', $comentarios);
        $stmt->bindParam(':nomencladores', $nomencladores);
        $stmt->bindParam(':categoriascie', $categoriascie);
        $stmt->bindParam(':domicilio', $domicilio);
        $stmt->bindParam(':localidad', $localidad);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':edad', $edad);
        $stmt->bindParam(':tipoDoc', $tipoDoc);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':firmante', $firmante);
        $stmt->bindParam(':id', $id);

        // Ejecutar la actualización
        $stmt->execute();

        $_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Datos modificados correctamente.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';

        // Redirigir a una página de éxito o realizar alguna otra acción
        header('Location: ../../public/layouts/seguimientoSolicitudes.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al procesar los datos. Vuelva a intentarlo o póngase en contacto con la administración.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
        // Manejar errores de la base de datos
        echo 'Error al actualizar la base de datos: ' . $e->getMessage();
    }
}