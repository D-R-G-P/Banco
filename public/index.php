<?php

$db = new DB();
$pdo = $db->connect();
$titulo_pestaña = "Inicio";


?>

<?php include_once 'layouts/bases/header.php'; ?>

<link rel="stylesheet" href="/Banco/public/css/index.css">
<article>
  <div class="banco">
    Banco:
    <select name="banco" id="bancoSelect">
      <option value="" disabled>Seleccione una opción</option>
      <?php
      try {
        $stmt = $pdo->prepare("SELECT banco, siglas FROM bancos");
        $stmt->execute();

        $options = "";
        $userBanco = $user->getBanco();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $banco = $row['banco'];
          $siglas = $row['siglas'];
          $selected = ($userBanco == $siglas) ? "selected" : "";

          $options .= "<option value='$siglas' $selected>$banco - $siglas</option>";
        }

        // Verificar si el banco almacenado no está en la lista
        $stmt->execute(); // Reiniciar el cursor del conjunto de resultados
        $found = false;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          if ($row['siglas'] == $userBanco) {
            $found = true;
            break;
          }
        }

        // Agregar opción para "Otro" si el banco almacenado no está en la lista
        if (!$found && $userBanco != "") {
          $options .= "<option value='otro' selected>Otro</option>";
        }

        // Escribir las opciones en el DOM
        echo $options;
      } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
      }
      ?>
    </select>


  </div>

  <hr>

  <div class="tablas" id="tablasContainer">
    <!-- Las tablas se generarán dinámicamente aquí -->
  </div>
</article>

<?php include_once 'layouts/bases/footer.php'; ?>

</body>
<script src="/Banco/public/js/index.js"></script>

</html>