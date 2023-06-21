<?php

require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';
require_once '../../app/modificarStock/searchBarcodeAdd.php';

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
  <title>S.C.S. - Inicio</title>
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

    <div class="acciones">
      <button class="anadir"><i class="fa-solid fa-plus"></i> Añadir stock</button>
      <button class="eliminar"><i class="fa-solid fa-minus"></i> Eliminar stock</button>
      <button class="agregar"><i class="fa-solid fa-file-circle-plus"></i> Añadir item</button>

      <div class="back">
        <div class="anadirForm">



          <button><i class="fa-solid fa-xmark"></i></button>!!!!!!!!



          <h2>Añadir stock</h2>
          <hr>
          <form action="" method="post">
            <label for="barcode">Código de barras</label>
            <div class="search">
              <input type="text" name="barcode" required id="barcode" value="" autofocus>
              <button type="submit" class="btn-verde"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
          </form>
          <div class="datos">
            <span>Item: <?php echo $item; ?></span>
            <span>Nombre: <?php echo $nombre; ?></span>
            <span>Categoría: <?php echo $categoria; ?></span>
            <span>Banco: <?php echo $banco; ?></span>
          </div>
          <form class="add" action="/Banco/app/modificarStock/anadirForm.php" method="post">
            <input type="hidden" name="codebar" value="<?php echo $barcode; ?>" id="codebar" required>
            <label for="stock">Stock a añadir</label>
            <input name="stock" type="number" min="1" value="1" required>
            <label for="lote">Lote</label>
            <input type="text" name="lote" required>

            <?php

            if ($boton == true) {
              echo '<button type="submit" class="btn-verde addB"><i class="fa-solid fa-plus"></i> Añadir</button>';
            } else {
              echo '<button type="submit" class="btn-verde addB disabled" disabled><i class="fa-solid fa-plus"></i> Añadir</button>';
            }

            ?>
          </form>
        </div>
      </div>









      <div class="aliminarForm"></div>
      <div class="agregarForm"></div>
    </div>

    <?php
    try {
      $stmt = $pdo->prepare("SELECT i.item, i.nombre, i.d_corta, i.d_larga, i.estudios, i.stock, i.categoria, b.banco
                        FROM items i
                        INNER JOIN bancos b ON i.banco = b.siglas
                        ORDER BY i.item ASC");
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
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item = $row['item'];
        $nombre = $row['nombre'];
        $d_corta = $row['d_corta'];
        $d_larga = $row['d_larga'];
        $estudios = $row['estudios'];
        $stock = $row['stock'];
        $categoria = $row['categoria'];
        $banco = $row['banco'];

        echo '<tr>';
        echo '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
        echo '<td style="vertical-align: middle;">' . $nombre . '</td>';
        echo '<td style="vertical-align: middle;">' . $d_corta . '</td>';
        echo '<td style="vertical-align: middle;">' . $d_larga . '</td>';
        echo '<td style="vertical-align: middle;">' . $estudios . '</td>';
        echo '<td style="text-align: center; vertical-align: middle;">' . $stock . '</td>';
        echo '<td style="text-align: center; vertical-align: middle;">' . $categoria . '</td>';
        echo '<td style="text-align: center; vertical-align: middle;">' . $banco . '</td>';
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

</html>