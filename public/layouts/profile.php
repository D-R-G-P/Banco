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
    <div class="accordion">
      <div class="accordion-item">
        <h2 class="accordion-heading">Agregar banco</h2>
        <div class="accordion-content">
          <form action="/Banco/app/profile/banco.php" method="post">
            <label for="banco">Nombre del banco</label>
            <input type="text" name="banco">

            <label for="sigla">Siglas</label>
            <input type="text" name="siglas">

            <button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar banco</button>
          </form>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-heading">Agregar categoria</h2>
        <div class="accordion-content">
          <form action="/Banco/app/profile/banco.php" method="post">
            <label for="banco">Nombre de la categoria</label>
            <input type="text" name="banco">

            <label for="sigla">Banco a la que pertenece</label>
            <select name="">
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

            <button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar banco</button>
          </form>
        </div>
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