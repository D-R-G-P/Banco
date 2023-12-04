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

$botonAnadir = false;
$back = false;
$anadirForm = false;

require_once '../../app/modificarStock/searchBarcode.php';


?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>S.C.S. - Modificar stock</title>
	<link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
	<link rel="stylesheet" href="/Banco/public/css/base.css">
	<link rel="stylesheet" href="/Banco/public/css/header.css">
	<link rel="stylesheet" href="/Banco/public/css/modificarStock.css">

	<!-- FontAwesome -->
	<script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
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

	<header>
		<div class="logo">
			<a href="/Banco/"><i class="fa-solid fa-dolly"></i></a>
		</div>

		<div class="links">
			<a href="/Banco/">Inicio</a>
			<a href="/Banco/public/layouts/modificarStock">Modificar stock</a>
			<a href="/Banco/public/layouts/seguimientoSolicitudes">Seguimiento</a>
			<a href="/Banco/public/layouts/realizarPedido" class="disabled">Realizar pedido</a>
		</div>

		<button id="user" class="user BORON">
			<i id="userI" class="fa-solid fa-user BORON"></i>
			<i id="flecha" class="fa-solid fa-caret-down BORON"></i>
		</button>

		<div id="userOptions" class="userOptions BORON">
			<div class="datos">
				<div>
					Bienvenido/a <br>
					<?php echo $user->getNombre() . " " . $user->getApellido(); ?>
				</div>
				<div>
					Perfil: <br>
					<?php echo $user->getTipo_usuario() ?>
				</div>
				<div>
					Cargo: <br>
					<?php echo $user->getCargo() ?>
				</div>

			</div>
			<div class="botones">
				<a class="profile" href="/Banco/public/layouts/profile">Ir a mi perfil</a>
				<a style="color: red;" href="/Banco/app/db/logout"><i class="fa-solid fa-power-off"></i> Cerrar sesión</a>
			</div>
		</div>
	</header>

	<article>

		<div class="acciones">
			<button class="anadir" onclick="toggleForm()"><i class="fa-solid fa-plus"></i> Nueva acción</button>
			<button class="action btn-verde" onclick="toggleAdd()"><i class="fa-solid fa-file-circle-plus"></i> Agregar item</button>
			<a class="stockPaciente btn-verde" href="/Banco/app/intervencion/intervenido.php" style="margin-left: 5vw; text-decoration: none;">
				<svg aria-hidden="true" style="font-size: 1vw" data-prefix="fas" data-icon="scalpel" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-scalpel fa-w-16 fa-7x">
					<path fill="currentColor" d="M482.7 11.85c-29.2-20.83-70.18-13.03-93.49 14.22l-201.5 235.46c-8.9 10.41-1.51 26.47 12.19 26.47h131.94c9.37 0 18.28-4.1 24.37-11.22l139.02-162.44c26.37-30.8 21.23-78.41-12.53-102.49zM0 512c87 .07 170.28-29.18 234.29-81.16.2-.16.39-.32.59-.48 31.37-25.71 46.72-63.93 46.72-102.32V320H176L0 512z" class=""></path>
				</svg>
				Informar paciente intervenido
			</a>


			<div class="back" id="back" style="<?php if ($back == true) {
													echo 'display: flex;';
												} else {
													echo 'display: none';
												} ?>">

				<div class="formCodebar" id="formCodebar" style="<?php if ($formCodebar == true) {
																		echo 'display: flex;';
																	} else {
																		echo 'display: none;';
																	} ?>">
					<div class="crossButton">
						<button onclick="toggleForm()" id="cross" class="btn-rojo"><i class="fa-solid fa-xmark"></i></button>
					</div>
					<h2>Modificar stock</h2>
					<div class="forms">
						<div class="formCode">
							<form action="" method="post">
								<label for="barcode">Código de barras</label>
								<div class="search">
									<input type="text" name="barcode" required id="barcode" value="" autofocus>
									<button type="submit" class="btn-verde"><i class="fa-solid fa-magnifying-glass"></i></button>
								</div>
							</form>
							<div class="datos">
								<span>Item: <?php echo isset($item) ? $item : ''; ?></span>
								<span>Nombre: <?php echo isset($nombre) ? $nombre : ''; ?></span>
								<span>Categoría: <?php echo isset($categoria) ? $categoria : ''; ?></span>
								<span>Banco: <?php echo isset($banco) ? $banco : ''; ?></span>
							</div>
							<div class="botones">
								<button onclick="openAdd()" class="btn-verde"><i class="fa-solid fa-plus"></i> Añadir
									stock</button>
								<button onclick="openRemove()" class="btn-rojo" style="height: min-content;"><i class="fa-solid fa-minus"></i> Eliminar stock</button>
							</div>
						</div>


						<form class="add" id="add" style="display: none;" action="/Banco/app/modificarStock/anadirForm.php" method="post">
							<input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>" id="id" required>
							<label for="stock">Stock a añadir</label>
							<input name="stock" type="number" min="1" value="1" required style="width: 20vw;">
							<label for="lote">Lote</label>
							<input type="text" name="lote" required style="width: 20vw;">
							<?php
							if ($botonAnadir == true) {
								echo '<button type="submit" class="btn-verde addB"><i class="fa-solid fa-plus"></i> Añadir</button>';
							} else {
								echo '<button type="submit" class="btn-verde addB disabled" disabled><i class="fa-solid fa-plus"></i> Añadir</button>';
							}
							?>
						</form>
						<form class="remove" id="remove" style="display: none;" action="/Banco/app/modificarStock/removeForm.php" method="post">
							<input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>" id="id" required>
							<label for="stock">Stock a Eliminar</label>
							<input name="stock" type="number" min="1" value="1" required style="width: 20vw;">
							<label for="lote">Lote</label>
							<input type="text" name="lote" required style="width: 20vw;">
							<?php
							if ($botonAnadir == true) {
								echo '<button type="submit" class="btn-rojo removeB"><i class="fa-solid fa-minus"></i> Eliminar</button>';
							} else {
								echo '<button type="submit" class="btn-rojo removeB disabled" disabled><i class="fa-solid fa-minus"></i> Eliminar</button>';
							}
							?>
						</form>
					</div>
				</div>


				<div class="agregarForm" id="agregarForm" style="display: none;">
					<div class="crossButtonAdd">
						<button onclick="toggleAdd()" id="cross" class="btn-rojo"><i class="fa-solid fa-xmark"></i></button>
					</div>
					<h2>Añadir item</h2>
					<form action="/Banco/app/modificarStock/addItem.php" method="post">
						<div class="divFormAdd">
							<div class="izquierda">
								<div>
									<label for="item">Número de item</label>
									<input type="number" name="item" required placeholder=" ">
								</div>

								<div>
									<label for="codigobarras">Código de barras</label>
									<input type="text" name="codigobarras" id="codigobarras" placeholder=" " required>
								</div>

								<div>
									<label for="nombre">Nombre</label>
									<input type="text" name="nombre" placeholder=" " required>
								</div>

								<div>
									<label for="banco">Banco</label>
									<select name="banco" id="banco" required placeholder=" " onchange="cargarCategorias()">
										<option value="" disabled selected></option>
										<?php
										// Obtener los bancos de la base de datos
										$stmt = $pdo->prepare("SELECT banco, siglas FROM bancos");
										$stmt->execute();
										while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
											$banco = $row['banco'];
											$siglas = $row['siglas'];
											echo "<option value='$siglas'>$banco - $siglas</option>";
										}
										?>
									</select>
								</div>

								<div>
									<label for="categoria">Categoría</label>
									<select name="categoria" id="categoria" required placeholder=" ">
										<option value="" disabled selected></option>
									</select>
								</div>
							</div>



							<div class="derecha">
								<div>
									<label for="dcorta">Descripcion corta</label>
									<textarea name="dcorta" placeholder=" " required></textarea>
								</div>

								<div>
									<label for="dlarga">Descripcion larga</label>
									<textarea name="dlarga" placeholder=" " required></textarea>
								</div>

								<div>
									<label for="estudios">Estudios</label>
									<textarea name="estudios" placeholder=" " required></textarea>
								</div>
							</div>
						</div>


						<div>
							<button class="btn-verde addEnviar" type="submit"><i class="fa-solid fa-file-circle-plus"></i> Agregar item</button>
						</div>
					</form>
				</div>
			</div>





			<?php
			try {
				$stmt = $pdo->prepare("SELECT i.id, i.item, i.nombre, i.d_corta, i.d_larga, i.estudios, i.stock, i.categoria, i.estado, i.barcode, b.banco FROM items i INNER JOIN bancos b ON i.banco = b.siglas WHERE i.estado != 'del' ORDER BY i.item ASC");
				$stmt->execute();

				echo '<table>';
				echo '<thead>';
				echo '<tr>';
				echo '<th style="text-align: center; vertical-align: middle;">Item</th>';
				echo '<th>Nombre</th>';
				echo '<th>Descripción corta</th>';
				echo '<th>Descripción larga</th>';
				echo '<th>Estudios</th>';
				echo '<th style="text-align: center; vertical-align: middle;">Stock</th>';
				echo '<th style="text-align: center; vertical-align: middle;">Categoría</th>';
				echo '<th style="text-align: center; vertical-align: middle;">Banco</th>';
				echo '<th style="text-align: center; vertical-align: middle;">Acciones</th>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';

				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$id = $row['id'];
					$item = $row['item'];
					$nombre = $row['nombre'];
					$d_corta = $row['d_corta'];
					$d_larga = $row['d_larga'];
					$estudios = $row['estudios'];
					$stock = $row['stock'];
					$categoria = $row['categoria'];
					$banco = $row['banco'];
					$estado = $row['estado'];

					echo '<tr>';
					echo '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
					echo '<td style="vertical-align: middle;">' . $nombre . '</td>';
					echo '<td style="vertical-align: middle;">' . $d_corta . '</td>';
					echo '<td style="vertical-align: middle;">' . $d_larga . '</td>';
					echo '<td style="vertical-align: middle;">' . $estudios . '</td>';
					echo '<td style="text-align: center; vertical-align: middle;">' . $stock . '</td>';
					echo '<td style="text-align: center; vertical-align: middle;">' . $categoria . '</td>';
					echo '<td style="text-align: center; vertical-align: middle;">' . $banco . '</td>';
					if ($estado == "act") {
						echo '<td style="vertical-align: middle; width: 8vw; text-align-last: justify;">
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/disable?id=' . $id . '" title="Deshabilitar item"><i class="fa-regular fa-circle-check"></i></i></a>
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/delete?id=' . $id . '" title="Eliminar item (no deberá haber stock disponible)"><i class="fa-solid fa-trash"></i></a>
						<a class="btn-verde actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/modificar?id=' . $id . '" title="Modificar stock de este item"><i class="fa-solid fa-pencil"></i></a>
							</td>';
					} else if ($estado == "des") {
						echo '<td style="vertical-align: middle; width: 8vw; text-align-last: justify;">
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/enable?id=' . $id . '" title="Habilitar item"><i class="fa-regular fa-circle-xmark"></i></a>
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/delete?id=' . $id . '" title="Eliminar item (no deberá haber stock disponible)"><i class="fa-solid fa-trash"></i></a>
						<a class="btn-rojo actionButton" style="font-size: 1.3vw;" href="/Banco/app/modificarStock/modificar?id=' . $id . '" title="Modificar stock de este item"><i class="fa-solid fa-pencil"></i></a>
					</td>';
					}
					echo '</tr>';
				}

				echo '</tbody>';
				echo '</table>';
			} catch (PDOException $e) {
				echo 'Error: ' . $e->getMessage();
			}
			?>




	</article>
	<footer>
		&copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
	</footer>

</body>

<script src="/Banco/public/js/header.js"></script>
<script src="/Banco/public/js/modificarStock.js"></script>

</html>