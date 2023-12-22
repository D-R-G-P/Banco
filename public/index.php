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
      <?php
      try {
        $stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
        $stmt->execute();

        $options = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $id_banco = $row['id'];
          $banco = $row['banco'];
          $siglas = $row['siglas'];
          $options .= "<option value='$id_banco'>$banco - $siglas</option>";
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