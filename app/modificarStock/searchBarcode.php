<?php

require_once '../../app/db/db.php';

$db = new DB();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtener el código de barras ingresado
  $barcode = $_POST['barcode'];

  try {
    // Consultar la información del elemento basado en el código de barras
    $stmt = $pdo->prepare("SELECT item, nombre, categoria, banco FROM items WHERE barcode = :barcode");
    $stmt->bindParam(':barcode', $barcode);
    $stmt->execute();

    // Verificar si se encontró un elemento con el código de barras ingresado
    if ($stmt->rowCount() > 0) {
      // Obtener los datos del elemento encontrado
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $item = $row['item'];
      $nombre = $row['nombre'];
      $categoria = $row['categoria'];
      $banco = $row['banco'];
      $botonAnadir = true;
      $back = true;
      $anadirForm = true;
    } else {
      // Si no se encontró ningún elemento, establecer los campos como vacíos
      $item = '';
      $nombre = '';
      $categoria = '';
      $banco = '';
      $botonAnadir = false;
      $back = true;
      $anadirForm = true;
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
} else {
  // Valores por defecto cuando el formulario se carga inicialmente
  $barcode = '';
  $item = '';
  $nombre = '';
  $categoria = '';
  $banco = '';
  $botonAnadir = false;
  $back = false;
  $anadirForm = false;
}
