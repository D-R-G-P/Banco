<!DOCTYPE html>
<html lang="es-AR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S.C.S. - Inicio</title>
  <link rel="stylesheet" href="/Banco/public/css/header.css">
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

    <button id="user" class="user" onclick="menuUser();">
      <i id="userI" class="fa-solid fa-user"></i>
      <i id="flecha" class="fa-solid fa-caret-down"></i>
    </button>

    <div id="userOptions" class="userOptions">
      <div class="datos">
        <div>
          Bienvenido/a <br>
          $user
        </div>
        <div>
          Perfil: <br>
          $permisos
        </div>
        <div>
          Cargo: <br>
          $cargo
        </div>
      </div>
      <div class="botones">
        <a class="profile" href="/Banco/public/layouts/profile.php">Ir a mi perfil</a>
        <a style="color: red;" href="/Banco/app/db/logout.php"><i class="fa-solid fa-power-off"></i> Cerrar sesión</a>
      </div>
    </div>
  </header>

  <article>
    <div class="banco">
      Banco: <select name="" id="">
        <option value="">Cirugía General - CIGE</option>
      </select>
    </div>

    <hr>

    <div class="tablas">
      <table>
        <thead>
          <th>Item</th>
          <th>Descripción</th>
          <th>Cantidad</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Endosutura de 35mm</td>
            <td>10</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Sutura circular de 35mm</td>
            <td>4</td>
          </tr>
        </tbody>
      </table>

      <table>
        <thead>
          <th>Item</th>
          <th>Descripción</th>
          <th>Cantidad</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Endosutura de 35mm</td>
            <td>10</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Sutura circular de 35mm</td>
            <td>4</td>
          </tr>
        </tbody>
      </table>

      <table>
        <thead>
          <th>Item</th>
          <th>Descripción</th>
          <th>Cantidad</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Endosutura de 35mm</td>
            <td>10</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Sutura circular de 35mm</td>
            <td>4</td>
          </tr>
        </tbody>
      </table>
    </div>
  </article>

  <footer>
    &copy; Dirección de Redes y Gestión de Personas
  </footer>

</body>

<script src="/Banco/public/js/header.js"></script>

</html>