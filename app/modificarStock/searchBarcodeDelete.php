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

    // Valores predeterminados para las variables
    $itemEliminar = 'Item no encontrado.';
    $nombreEliminar = 'Item no encontrado.';
    $categoriaEliminar = 'Item no encontrado.';
    $bancoEliminar = 'Item no encontrado.';
    $botonEliminar = false;
    $back = true;
    $eliminarForm = true;

    // Verificar si se encontró un elemento con el código de barras ingresado
    if ($stmt->rowCount() > 0) {
      // Obtener los datos del elemento encontrado
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $itemEliminar = $row['item'];
      $nombreEliminar = $row['nombre'];
      $categoriaEliminar = $row['categoria'];
      $bancoEliminar = $row['banco'];
      $botonEliminar = true;
      $back = true;
      $eliminarForm = true;
    }
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
} else {
  // Valores por defecto cuando el formulario se carga inicialmente
  $barcodeEliminar = '';
  $itemEliminar = '';
  $nombreEliminar = '';
  $categoriaEliminar = '';
  $bancoEliminar = '';
  $botonEliminar = false;
}

// Enviar el formulario al archivo anadirForm.php
