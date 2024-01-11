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

$titulo_pestaña = "Realizar pedido";

?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Recoger los datos del formulario
	$tipo_solicitud = 'Para cirugía';
	$fecha_solicitud = $_POST['fecha_solicitud'];
	$GDEBA = '';
	// $items_JSON = $_POST['jsonItems'];
	$paciente = $_POST['paciente'];
	$dni = $_POST['dni'];
	$telefono = $_POST['telefono'];
	$estado = ''; // Puedes establecer un valor por defecto para el estado
	$tipo_cirugia = ''; // Puedes establecer un valor por defecto para el tipo de cirugía
	$fecha_perfeccionamiento = ''; // Puedes establecer un valor por defecto para la fecha de perfeccionamiento
	$sol_provision = ''; // Puedes establecer un valor por defecto para la solución de provisión
	$fecha_cirugia = ''; // Puedes establecer un valor por defecto para la fecha de cirugía
	$comentarios = ''; // Puedes establecer un valor por defecto para los comentarios
	// Inicializar el array para almacenar los datos
	$nomencladores = $_POST['nomencladores'];
	$categoriascie = $_POST['categoriascie'];
	$banco = $_POST['banco'];
	$firmante = $user->getMatricula();
	$intervencion = 'no';
	$arrayItems = array();

	// Recorrer los datos del formulario
	foreach ($_POST['material'] as $key => $value) {
		// Verificar si la cantidad es mayor o igual a 1
		if ($value >= 1) {
			// Extraer id y item de la clave
			$idStart = strpos($key, "id:") + 4;
			$idEnd = strpos($key, ",");
			$id = substr($key, $idStart, $idEnd - $idStart);

			// Crear el array con los datos necesarios
			$arrayItem = array('id' => $id, 'cantidad' => $value);

			// Agregar el array al array principal
			array_push($arrayItems, $arrayItem);
		}
	}

	// Convertir el array a formato JSON
	$items_JSON = json_encode($arrayItems);

	try {
		// Crear la consulta de inserción
		$query = "INSERT INTO solicitudes (tipo_solicitud, fecha_solicitud, items_JSON, paciente, dni, telefono, nomencladores, categoriascie, banco, firmante, intervencion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		// Preparar la consulta
		$stmt = $pdo->prepare($query);

		// Ejecutar la consulta
		$stmt->execute([$tipo_solicitud, $fecha_solicitud, $items_JSON, $paciente, $dni, $telefono, $nomencladores, $categoriascie, $banco, $firmante, $intervencion]);

		// Obtener el último ID insertado
		$lastInsertId = $pdo->lastInsertId();

		// Mostrar un mensaje de éxito
		$_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Paciente y material nominalizado correctamente.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';

		// Redirigir a otra página después de la inserción
		header("Location: realizarPedido.php");
		exit(); // Asegurar que no se ejecute nada más después de la redirección

	} catch (PDOException $e) {
		// Mostrar un mensaje de error en caso de que ocurra un error en la consulta
		$_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al nominalizar. Vuelva a intentarlo o póngase en contacto con la administración. ' . $e->getMessage() . '</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
		echo 'Error: ' . $e->getMessage();

		header("Location: realizarPedido.php");
		exit(); // Asegurar que no se ejecute nada más después de la redirección
	}
}
?>

<?php include_once 'bases/header.php'; ?>
<link rel="stylesheet" href="/Banco/public/css/realizarPedido.css">
<link rel="stylesheet" href="/Banco/public/css/table.css">

<?php
if (isset($_SESSION['success_message'])) {
	echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
	// Borrar el mensaje de éxito de la variable de sesión para no mostrarlo nuevamente
	unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
	echo '<div class="error-message">' . $_SESSION['error_message'] . '</div>';
	// Borrar el mensaje de éxito de la variable de sesión para no mostrarlo nuevamente
	unset($_SESSION['error_message']);
}
?>


<article style="padding: 1vw">

	<?php
	
	if ($user->getTipo_usuario() != "Cirujano") {
		include_once "realizar/admi.php";
	} elseif ($user->getTipo_usuario() == "Cirujano") {
		include_once "realizar/ciruja.php";
	}
	
	?>

</article>



<?php include_once 'bases/footer.php'; ?>

</body>

<script src="/Banco/public/js/realizarPedido.js"></script>







</html>