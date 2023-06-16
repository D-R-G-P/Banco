<!DOCTYPE html>
<html lang="es-AR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S.C.S. - Inicio</title>
  <link rel="stylesheet" href="/Banco/public/css/index.css">

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

    <div class="user">
      <i class="fa-solid fa-user"></i>
    </div>

    <div class="userOptions">
      Bienvenido/a <br>
      $user <br>
      Perfil: <br>
      $permisos <br>
      Cargo: <br>
      $cargo
    </div>
  </header>

  <article>
    ESTADISTICA
  </article>
</body>

</html>