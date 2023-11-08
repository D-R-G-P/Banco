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
		<form action="">
			<div class="left">
				<div>
					<label for="tipo_solicitud">Tipo de solicitud:</label>
					<select name="tipo_solicitud" id="tipo_solicitud" aria-readonly="true">
						<option value="Para nominalizar stock" selected>Para nominalizar stock</option>
					</select>
				</div>
				<div>
					<label for="fecha_solicitud">Fecha de solicitud:</label>
					<input type="date" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div>
					<label for="paciente">Nombre completo del paciente:</label>
					<input type="text" id="paciente" name="paciente">
				</div>
				<div>
					<label for="dni">D.N.I:</label>
					<input type="text" oninput="formatDNI(this)">
				</div>
				<div>
					<label for="banco">Banco:</label>
					<select name="banco" id="banco">
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
				<table id="tabla-resultados">
					<thead>
						<tr>
							<th>Item</th>
							<th>Nombre</th>
							<th>Descripción</th>
							<th>Cantidad</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>asd</td>
							<td>asdasd</td>
							<td><input type="number"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
	</article>





	<script>
		// Obtener la fecha actual en el formato "dd/mm/yyyy" para el input date
		var hoy = new Date();
		var dd = String(hoy.getDate()).padStart(2, '0');
		var mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Enero es 0
		var yyyy = hoy.getFullYear();

		var fechaHoy = dd + '/' + mm + '/' + yyyy;
		document.getElementById('fecha').setAttribute('max', yyyy + '-' + mm + '-' + dd);
		document.getElementById('fecha').setAttribute('value', yyyy + '-' + mm + '-' + dd);


		// Obtener y formatear el campo de DNI
		function formatDNI(input) {
			// Obtener el valor del input y eliminar cualquier carácter no numérico
			var num = input.value.replace(/\D/g, '');

			// Si hay al menos un número
			if (num) {
				// Formatear el número con puntos
				var formattedNum = '';
				for (var i = 0; i < num.length; i++) {
					if (i > 0 && i % 3 === 0) {
						formattedNum = '.' + formattedNum;
					}
					formattedNum = num[num.length - 1 - i] + formattedNum;
				}

				// Establecer el valor formateado en el input
				input.value = formattedNum;
			} else {
				input.value = '';
			}
		}

		document.getElementById('banco').addEventListener('change', function() {
			var selectedBanco = this.value;
			if (selectedBanco) {
				fetch('/Banco/app/intervencion/gettable.php?banco=' + selectedBanco)
					.then(function(response) {
						return response.json();
					})
					.then(function(data) {
						// Manipular los datos y actualizar la tabla aquí
						var tablaResultados = document.getElementById('tabla-resultados').getElementsByTagName('tbody')[0];
						tablaResultados.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos
						data.forEach(function(row) {
							var newRow = tablaResultados.insertRow(-1);
							newRow.insertCell(0).appendChild(document.createTextNode(row.item));
							newRow.insertCell(1).appendChild(document.createTextNode(row.nombre));
							newRow.insertCell(2).appendChild(document.createTextNode(row.d_corta));
							var inputCell = newRow.insertCell(3);
							var input = document.createElement('input');
							input.type = 'number';
							inputCell.appendChild(input);
						});
					})
					.catch(function(error) {
						console.log('Error: ' + error);
					});
			}
		});
	</script>

</body>

</html>