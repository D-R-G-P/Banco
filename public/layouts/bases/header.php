<!DOCTYPE html>
<html lang="es-AR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="copyright" content="Dirección de Redes y Gestión de Personas - H.I.G.A. General San Martín" />
    <meta name="author" content="Lamas Cristian Jonathan" />
    <title>S.G.B. - <?php echo $titulo_pestaña ?></title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/base.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>

    <script src="/Banco/node_modules/jquery/dist/jquery.min.js"></script>
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
            <a href="/Banco/public/layouts/realizarPedido">Realizar pedido</a>
        </div>

        <button class="perfil" id="perfilButton" style="cursor: pointer;">
            <i class="fa-solid fa-user-large iconoPerfil"></i>
            <div class="arrow" id="arrow">
                <i id="iconoFlecha" class="fa-solid fa-caret-down iconoFlecha"></i>
            </div>
        </button>

        <div class="perfilMenu" id="perfilMenu">

            <div class="cabeza">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle iconoMenuUsuario" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"></path>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z">
                    </path>
                </svg>

                <span style="margin-top: 1.3vw; font-weight: 700;"><?php echo $user->getNombre() . " " . $user->getApellido(); ?></span>
                <span style="margin-top: .2vw; text-transform: uppercase; font-weight: 400; color: grey;"><?php echo $user->getUsername() ?></span>
            </div>


            <div class="perfil modulo">
                <span style="color: grey; margin-top: .8vw;"><?php echo $user->getCargo() ?></span>
                <span style="color: grey; margin-top: .2vw;"><?php echo $user->getTipo_usuario() ?></span>
            </div>


            <div class="botones modulo">

                <button onclick="window.location.href='/Banco/public/layouts/profile.php'">
                    <p>Ir a Perfil <i class="fa-solid fa-user"></i></p>
                </button>

                <button onclick="window.location.href='/Banco/app/db/logout.php'" style="border-radius: 0 0 .8vw .8vw; color: red;">
                    <p>Cerrar sesión <i class="fa-solid fa-power-off"></i></p>
                </button>

            </div>


        </div>
    </header>
    <script src="/Banco/public/js/header.js"></script>