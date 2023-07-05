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
	<title>S.C.S. - Mi usuario</title>
	<link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
	<link rel="stylesheet" href="/Banco/public/css/base.css">
	<link rel="stylesheet" href="/Banco/public/css/header.css">
	<link rel="stylesheet" href="/Banco/public/css/profile.css">

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
	?>
	<header>
		<div class="logo">
			<a href="/Banco/"><i class="fa-solid fa-dolly"></i></a>
		</div>

		<div class="links">
			<a href="/Banco/">Inicio</a>
			<a href="/Banco/public/layouts/modificarStock.php">Modificar stock</a>
			<a href="/Banco/public/layouts/seguimientoSolicitudes.php" class="disabled">Seguimiento</a>
			<a href="/Banco/public/layouts/realizarPedido.php" class="disabled">Realizar pedido</a>
		</div>

		<button id="user" class="user" onclick="menuUser();">
			<i id="userI" class="fa-solid fa-user"></i>
			<i id="flecha" class="fa-solid fa-caret-down"></i>
		</button>

		<div id="userOptions" class="userOptions">
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
				<a class="profile" href="/Banco/public/layouts/profile.php">Ir a mi perfil</a>
				<a style="color: red;" href="/Banco/app/db/logout.php"><i class="fa-solid fa-power-off"></i> Cerrar
					sesión</a>
			</div>
		</div>
	</header>

	<article>
		<div class="accordion">
			<div class="accordion-item">


				<h2 class="accordion-heading">Crear usuario</h2>
				<div class="accordion-content">
					<form action="/Banco/app/profile/crear.php" method="post">

						<label for="nombre">Nombres</label>
						<input type="text" name="nombre" required>

						<label for="apellido">Apellidos</label>
						<input type="text" name="apellido" required>

						<label for="dni">D.N.I.</label>
						<input type="text" name="dni" required>

						<label for="username">Nombre de usuario</label>
						<input type="text" name="username" required>

						<label for="password">Contraseña</label>
						<input type="text" name="password" value="1234" readonly>

						<label for="cargo">Cargo</label>
						<input type="text" name="cargo" required>

						<label for="banco">Banco</label>
						<select name="banco" required>
							<option value="" selected disabled>Seleccionar una opción</option>

							<?php
							try {
								$stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
								$stmt->execute();

								$options = "";
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									$id_banco = $row['id'];
									$banco = $row['banco'];
									$siglas = $row['siglas'];
									$options .= "<option value='$siglas'>$banco - $siglas</option>";
								}

								// Escribir las opciones en el DOM
								echo $options;
							} catch (PDOException $e) {
								echo 'Error: ' . $e->getMessage();
							}
							?>

							<option value="Otro">Otro</option>
						</select>

						<label for="tipo_usuario">Tipo de usuario</label>
						<select name="tipo_usuario" required>
							<option value="" disabled selected>Seleccionar una opcion</option>
							<option value="SuperAdmin">SuperAdmin</option>
							<option value="Admin">Admin</option>
							<option value="Deposito">Deposito</option>
							<option value="Instrumentador">Instrumentador</option>
							<option value="Cirujano">Cirujano</option>
						</select>

						<button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Crear usuario</button>
					</form>
				</div>
			</div>
			<div class="accordion-item">


				<h2 class="accordion-heading">Agregar banco</h2>
				<div class="accordion-content">
					<form action="/Banco/app/profile/banco.php" method="post">
						<label for="banco">Nombre del banco</label>
						<input type="text" name="banco" required>

						<label for="sigla">Siglas</label>
						<input type="text" name="siglas" required>

						<button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar banco</button>
					</form>
				</div>
			</div>
			<div class="accordion-item">


				<h2 class="accordion-heading">Agregar categoria</h2>
				<div class="accordion-content">
					<form action="/Banco/app/profile/categoria.php" method="post">
						<label for="categoria">Nombre de la categoria</label>
						<input type="text" name="categoria" required>

						<label for="banco">Banco al que pertenece</label>
						<select name="banco" required>
							<option value="" disabled selected>Seleccione una opción</option>

							<?php
							try {
								$stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
								$stmt->execute();

								$options = "";
								while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									$id_banco = $row['id'];
									$banco = $row['banco'];
									$siglas = $row['siglas'];
									$options .= "<option value='$siglas'>$banco - $siglas</option>";
								}

								// Escribir las opciones en el DOM
								echo $options;
							} catch (PDOException $e) {
								echo 'Error: ' . $e->getMessage();
							}
							?>
						</select>

						<button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar
							categoria</button>
					</form>
				</div>
			</div>
		</div>

		<div class="datos">
			<h2 class="datos-cabeza">Modificar mis datos</h2>
			<div class="datos-contenido">
				<form action="/Banco/app/profile/modificar.php" method="post">

					<label for="nombre">Nombres</label>
					<input type="text" name="nombre" value="<?php echo $user->getNombre(); ?>" required>

					<label for="apellido">Apellidos</label>
					<input type="text" name="apellido" value="<?php echo $user->getApellido(); ?>" required>

					<label for="dni">D.N.I.</label>
					<input type="text" name="dni" value="<?php echo $user->getDni(); ?>" required>

					<label for="username">Nombre de usuario</label>
					<input type="text" name="username" value="<?php echo $user->getUsername(); ?>" required disabled>

					<label for="password">Contraseña</label>
					<input type="password" name="password">

					<label for="cargo">Cargo</label>
					<input type="text" name="cargo" value="<?php echo $user->getCargo(); ?>" required>

					<label for="banco">Banco</label>
					<select name="banco" required disabled>
						<?php
						try {
							$stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
							$stmt->execute();

							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$id_banco = $row['id'];
								$banco = $row['banco'];
								$siglas = $row['siglas'];

								if ($user->getBanco() == $siglas) {
									echo "<option value='$siglas' selected>$banco - $siglas</option>";
								} else {
									echo "<option value='$siglas'>$banco - $siglas</option>";
								}
							}
						} catch (PDOException $e) {
							echo 'Error: ' . $e->getMessage();
						}
						?>
						<option value="Otro">Otro</option>
					</select>

					<label for="tipo_usuario">Tipo de usuario</label>
					<select name="tipo_usuario" required disabled>
						<option value="" disabled selected>Seleccionar una opción</option>
						<option value="SuperAdmin" <?php if ($user->getTipo_usuario() == "SuperAdmin") echo "selected"; ?>>SuperAdmin</option>
						<option value="Admin" <?php if ($user->getTipo_usuario() == "Admin") echo "selected"; ?>>Admin</option>
						<option value="Deposito" <?php if ($user->getTipo_usuario() == "Deposito") echo "selected"; ?>>Deposito</option>
						<option value="Instrumentador" <?php if ($user->getTipo_usuario() == "Instrumentador") echo "selected"; ?>>Instrumentador</option>
						<option value="Cirujano" <?php if ($user->getTipo_usuario() == "Cirujano") echo "selected"; ?>>Cirujano</option>
					</select>

					<button type="submit" class="btn-verde"><i class="fa-solid fa-pencil"></i> Modificar datos</button>
				</form>
			</div>
		</div>

	</article>


	<footer>
		&copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
	</footer>
</body>
<script src="/Banco/public/js/header.js"></script>
<script src="/Banco/public/js/profile.js"></script>

</html>