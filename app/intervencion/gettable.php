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
    $html = '';

    // Consulta para obtener los datos de la tabla "items" filtrados por el id del banco y la categoría actual
    $itemsQuery = "SELECT id, item, nombre FROM items WHERE banco = :idBanco AND (stock = 1 OR stock > 1) AND estado != 'del' ORDER BY item ASC";
    $itemsStatement = $pdo->prepare($itemsQuery);
    $itemsStatement->bindParam(':idBanco', $idBanco);
    $itemsStatement->execute();

    while ($itemRow = $itemsStatement->fetch(PDO::FETCH_ASSOC)) {
        $id = $itemRow['id'];
        $item = $itemRow['item'];
        $nombre = $itemRow['nombre'];

        $html .= '<tr>';
        $html .= '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
        $html .= "<td>$nombre</td>";
        $html .= '<td><input type="number" name="material[id: ' . $id . ',]"></td>';
        $html .= '</tr>';
    }
    // Devolver el HTML generado
    echo $html;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
