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
?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>S.C.S. - Nominalizando paciente</title>
	<link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
	<link rel="stylesheet" href="/Banco/public/css/base.css">
	<link rel="stylesheet" href="/Banco/public/css/header.css">
	<link rel="stylesheet" href="/Banco/public/css/table.css">
	<link rel="stylesheet" href="/Banco/public/css/intervenido.css">

	<!-- FontAwesome -->
	<script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>

	<article>
		<form action="#" method="post">
			<div class="left">
				<div>
					<label for="tipo_solicitud">Tipo de solicitud:</label>
					<select name="tipo_solicitud" id="tipo_solicitud" aria-readonly="true">
						<option value="Para nominalizar stock" selected>Para nominalizar stock</option>
					</select>
				</div>
				<div>
					<label for="fecha_solicitud">Fecha de solicitud:</label>
					<input name="fecha_solicitud" type="date" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" required>
				</div>
				<div>
					<label for="paciente">Nombre completo del paciente:</label>
					<input type="text" id="paciente" name="paciente" required>
				</div>
				<div>
					<label for="dni">D.N.I:</label>
					<input name="dni" type="text" oninput="formatDNI(this)" required>
				</div>
				<div>
					<label for="banco">Banco:</label>
					<select name="banco" id="bancoSelect" onchange="carga()" required>
						<option value="" selected disabled>Seleccionar banco</option>
						<?php
						try {
							$query = "SELECT banco, siglas FROM bancos";
							$stmt = $pdo->prepare($query);
							$stmt->execute();

							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo '<option value="' . $row['siglas'] . '">' . $row['banco'] . ' - ' . $row['siglas'] . '</option>';
							}
						} catch (PDOException $e) {
							echo 'Error: ' . $e->getMessage();
						}
						?>
					</select>
				</div>
			</div>

			<div class="right">


				<table>
					<thead>
						<tr>
							<th>Item</th>
							<th>Nombre</th>
							<th>Cantidad</th>
						</tr>
					</thead>
					<tbody id="tabla-resultados"></tbody>
					<tbody id="tablaCruda">
						<tr style="text-align: center; font-size: 1.4vw;">
							<td colspan="3" style="text-align: center; padding: .5vw;">Seleccione un banco para continuar</td>
						</tr>
					</tbody>
				</table>
			</div>



			<input type="submit" class="btn-verde sendButton" value="Nominalizar uso de material">
		</form>
	</article>

	<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Recoger los datos del formulario
		$tipo_solicitud = 'Para nominalizar stock';
		$fecha_solicitud = $_POST['fecha_solicitud'];
		$GDEBA = '';
		$items_JSON = $_POST['jsonItems'];
		$paciente = $_POST['paciente'];
		$dni = $_POST['dni'];
		$estado = ''; // Puedes establecer un valor por defecto para el estado
		$tipo_cirugia = ''; // Puedes establecer un valor por defecto para el tipo de cirugía
		$fecha_perfeccionamiento = ''; // Puedes establecer un valor por defecto para la fecha de perfeccionamiento
		$sol_provision = ''; // Puedes establecer un valor por defecto para la solución de provisión
		$fecha_cirugia = ''; // Puedes establecer un valor por defecto para la fecha de cirugía
		$comentarios = ''; // Puedes establecer un valor por defecto para los comentarios
		// Inicializar el array para almacenar los datos
		$banco = $_POST['banco'];
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
			$query = "INSERT INTO solicitudes (tipo_solicitud, fecha_solicitud, items_JSON, paciente, dni, banco, intervencion) VALUES (?, ?, ?, ?, ?, ?, ?)";

			// Preparar la consulta
			$stmt = $pdo->prepare($query);

			// Ejecutar la consulta
			$stmt->execute([$tipo_solicitud, $fecha_solicitud, $items_JSON, $paciente, $dni, $banco, $intervencion]);

			// Obtener el último ID insertado
			$lastInsertId = $pdo->lastInsertId();

			// Actualizar el stock en la tabla items
			foreach ($arrayItems as $item) {
				$itemId = $item['id'];
				$cantidad = $item['cantidad'];

				// Consulta para restar la cantidad al stock actual
				$updateStockQuery = "UPDATE items SET stock = stock - :cantidad WHERE id = :itemId";

				// Preparar la consulta de actualización
				$updateStockStmt = $pdo->prepare($updateStockQuery);

				// Bind de los parámetros
				$updateStockStmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
				$updateStockStmt->bindParam(':itemId', $itemId, PDO::PARAM_INT);

				// Ejecutar la consulta de actualización
				$updateStockStmt->execute();
			}

			// Mostrar un mensaje de éxito
			$_SESSION['success_message'] = '<div class="notisContent"><div class="notis" id="notis">Paciente y material nominalizado correctamente.</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';

			// Redirigir a otra página después de la inserción
			header("Location: ../../public/layouts/modificarStock.php");
			exit(); // Asegurar que no se ejecute nada más después de la redirección

		} catch (PDOException $e) {
			// Mostrar un mensaje de error en caso de que ocurra un error en la consulta
			$_SESSION['error_message'] = '<div class="notisContent"><div class="notiserror" id="notis">Error al nominalizar. Vuelva a intentarlo o póngase en contacto con la administración. ' . $e->getMessage() . '</div></div><script>setTimeout(() => {notis.classList.toggle("active");out();}, 1);function out() {setTimeout(() => {notis.classList.toggle("active");}, 2500);}</script>';
			echo 'Error: ' . $e->getMessage();

			header("Location: ../../public/layouts/modificarStock.php");
			exit(); // Asegurar que no se ejecute nada más después de la redirección
		}
	}
	?>

	<script src="/Banco/public/js/intervenido.js"></script>
</body>

</html>