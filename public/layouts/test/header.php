<?php

require_once '../../../app/db/user_session.php';
require_once '../../../app/db/user.php';
require_once '../../../app/db/db.php';

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
    <title>S.C.S. - Modificar stock</title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/modificarStock.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>


    <style>
        /* header DIV */

        header .perfil {
            display: flex;
            background: none;
            border: none;
            cursor: pointer;
            align-items: center;
        }

        header .perfil .iconoPerfil {
            color: #fff;
            font-size: 2.3vw;
        }

        header .perfil .arrow {
            display: flex;
            align-items: flex-end;
            margin-left: 0.3vw;
        }

        header .perfil .arrow .iconoFlecha {
            color: #fff;
            font-size: 1.5vw;
        }

        header .perfilMenu .modulo {
            display: flex;
            flex-direction: column;
            margin-top: 0.8vw;
        }

        header .perfilMenu .cabeza {
            display: flex;
            text-align: center;
            align-items: center;
            flex-direction: column;
            padding: 2.5vw 2.5vw 0.8vw 2.5vw;
        }

        header .perfilMenu .cabeza .iconoMenuUsuario {
            width: 5vw;
            height: 5vw;
            color: #696969;
        }

        header .perfilMenu .perfil {
            display: flex;
            flex-direction: column;
            text-align: center;
            padding: 0vw 2.5vw 2.5vw 2.5vw;
        }

        header .perfilMenu .botones button {
            width: 100%;
            border-top: 0.1vw grey solid;
            border-left: none;
            border-bottom: none;
            border-right: none;
            padding: 1vw 0;
            background: transparent;
            cursor: pointer;
            color: #323232;
            transition: all 0.15s ease-in-out;
        }

        header .perfilMenu .botones button:hover {
            background-color: #39393ef5;
            color: #fff;
        }

        header .perfilMenu .botones button p {
            text-decoration: none;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            font-size: 1.3vw;
            margin: 0 2vw;
        }

        #arrow {
            transition: transform .2s ease-in-out;
        }

        .rotar {
            transform: rotate(180deg);
            transition: transform .2s ease-in-out;
        }

        header .perfilMenu {
            max-height: 0vw;
            overflow: hidden;
            position: absolute;
            flex-direction: column;
            background-color: transparent;
            border-radius: 0.8vw;
            right: 1vw;
            top: 4.3vw;
            border: transparent;
            box-shadow: transparent;
        }

        header .perfilMenu.cerrar {
            animation: cerrarMenu 0.5s ease-in-out forwards;
        }

        .perfilMenu.mostrar {
            animation: abrirMenu 0.5s ease-in-out forwards;
            border: 0.23vw rgba(0, 0, 0, 0.175) solid;
            background-color: #fff;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
        }


        @keyframes abrirMenu {
            0% {
                background-color: #fff;
                border: 0.23vw rgba(0, 0, 0, 0.175) solid;
                box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
            }

            1% {
                max-height: 0vh;
            }

            100% {
                max-height: 100vh;
            }
        }

        @keyframes cerrarMenu {
            0% {
                background-color: #fff;
                border: 0.23vw rgba(0, 0, 0, 0.175) solid;
                box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
                max-height: 100vh;
            }

            99% {
                max-height: 0vh;
                background-color: #fff;
                border: 0.23vw rgba(0, 0, 0, 0.175) solid;
                box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2);
            }

            100% {
                background-color: transparent;
                border: transparent;
                box-shadow: transparent;
                max-height: 0vh;
            }
        }
    </style>
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

        <button class="perfil" id="perfilButton">
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




    <script>
        // Obtener referencias a los elementos del DOM
        var perfilButton = document.getElementById('perfilButton');
        var perfilMenu = document.getElementById('perfilMenu');
        var iconoFlecha = document.getElementById('arrow');

        // Agregar evento de clic al botón
        perfilButton.addEventListener('click', function() {
            // Verificar si el menú tiene la clase "mostrar"
            var menuEstaVisible = perfilMenu.classList.contains('mostrar');

            if (menuEstaVisible) {
                // Si el menú está visible, ciérralo
                cerrarMenu();
            } else {
                // Si el menú no está visible, ábrelo
                abrirMenu();
            }
        });

        // Agregar evento de clic al documento para cerrar el menú si se hace clic fuera de él
        document.addEventListener('click', function(event) {
            var isClickInside = perfilButton.contains(event.target) || perfilMenu.contains(event.target);
            if (!isClickInside) {
                // Si el clic no fue dentro del botón o del menú, cierra el menú
                cerrarMenu();
            }
        });

        // Función para abrir el menú
        function abrirMenu() {
            perfilMenu.classList.add('mostrar')
            iconoFlecha.classList.add('rotar');
        }

        // Función para cerrar el menú
        function cerrarMenu() {
            if (perfilMenu.classList.contains('mostrar')) {
                perfilMenu.classList.add('cerrar');
                setTimeout(cerrarTodo, 500);
            }
            perfilMenu.classList.remove('mostrar');
            iconoFlecha.classList.remove('rotar');
        }

        function cerrarTodo() {
            perfilMenu.classList.remove('cerrar');
        }
    </script>