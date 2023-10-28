<?php

require_once '../../app/db/db.php';

$db = new DB();
$pdo = $db->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtener el código de barras ingresado
  $barcode = $_POST['barcode'];

  try {
    // Consultar la información de los elementos basada en el código de barras
    $stmt = $pdo->prepare("SELECT id, item, nombre, categoria, banco FROM items WHERE barcode = :barcode");
    $stmt->bindParam(':barcode', $barcode);
    $stmt->execute();

    // Verificar si se encontró algún elemento con el código de barras ingresado
    if ($stmt->rowCount() == 1) {
      // Obtener los datos del elemento encontrado
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $id = $row['id'];
      $item = $row['item'];
      $nombre = $row['nombre'];
      $categoria = $row['categoria'];
      $banco = $row['banco'];
      $botonAnadir = true;
      $back = true;
      $formCodebar = true;
    } elseif ($stmt->rowCount() >= 2) {
      // Redirigir a la página de selección del elemento
      header('Location: select_item.php?barcode=' . $barcode);
      exit;
    } else {
      // Si no se encontró ningún elemento, establecer los campos como vacíos
      $item = 'Item no encontrado';
      $nombre = 'Item no encontrado';
      $categoria = 'Item no encontrado';
      $banco = 'Item no encontrado';
      $botonAnadir = false;
      $back = true;
      $formCodebar = true;
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
  $formCodebar = false;
}