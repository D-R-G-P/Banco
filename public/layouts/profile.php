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
        <div class="banco">
            <h2>Agregar banco</h2>
            <form action="/Banco/app/profile/banco.php" method="post">
                <label for="banco">Nombre del banco</label>
                <input type="text" name="banco">

                <label for="sigla">Siglas</label>
                <input type="text" name="siglas">

                <button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar banco</button>
            </form>
        </div>
    </article>

    <footer>
        &copy; Dirección de Redes y Gestión de Personas. Todos los derechos reservados
    </footer>
</body>
<script src="/Banco/public/js/header.js"></script>

</html>