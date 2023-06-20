<?php
// Importar el archivo db.php
require_once('../db/db.php');


// Crear una instancia de la clase DB
$db = new DB();

// Establecer conexión a la base de datos
$pdo = $db->connect();

// Obtener el id del banco enviado por la solicitud
$idBanco = $_GET['idBanco'];

try {
  // Consulta para obtener las categorías relacionadas al banco
  $query = "SELECT DISTINCT categoria FROM items WHERE banco = (SELECT siglas FROM bancos WHERE id = :idBanco)";
  $statement = $pdo->prepare($query);
  $statement->bindParam(':idBanco', $idBanco);
  $statement->execute();

  // Generar la estructura HTML de las tablas por cada categoría
  $html = '';
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $categoria = $row['categoria'];

    $html .= '<div>';
    $html .= "<h2>$categoria</h2>";
    $html .= '<table>';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Item</th>';
    $html .= '<th>Descripción</th>';
    $html .= '<th>Cantidad</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';

    // Consulta para obtener los datos de la tabla "items" filtrados por el id del banco y la categoría actual
    $itemsQuery = "SELECT item, nombre, stock FROM items WHERE banco = (SELECT siglas FROM bancos WHERE id = :idBanco) AND categoria = :categoria";
    $itemsStatement = $pdo->prepare($itemsQuery);
    $itemsStatement->bindParam(':idBanco', $idBanco);
    $itemsStatement->bindParam(':categoria', $categoria);
    $itemsStatement->execute();

    while ($itemRow = $itemsStatement->fetch(PDO::FETCH_ASSOC)) {
      $item = $itemRow['item'];
      $nombre = $itemRow['nombre'];
      $stock = $itemRow['stock'];

      $html .= '<tr>';
      $html .= "<td>$item</td>";
      $html .= "<td>$nombre</td>";
      $html .= "<td>$stock</td>";
      $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
  }

  // Devolver el HTML generado
  echo $html;
} catch (PDOException $e) {
  echo 'Error: ' . $e->getMessage();
}
