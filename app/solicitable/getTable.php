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
    $itemsQuery = "SELECT id, banco, item, descripcion, descripcionAmpliada, estPre, estPos, estado
    FROM itemssolicitables
    WHERE banco = :idBanco AND estado = 'act'
    ORDER BY CAST(item AS SIGNED) ASC;
    ";
    $itemsStatement = $pdo->prepare($itemsQuery);
    $itemsStatement->bindParam(':idBanco', $idBanco);
    $itemsStatement->execute();

    while ($itemRow = $itemsStatement->fetch(PDO::FETCH_ASSOC)) {
        $id = $itemRow['id'];
        $banco = $itemRow['banco'];
        $item = $itemRow['item'];
        $descripcion = $itemRow['descripcion'];
        $descripcionAmpliada = $itemRow['descripcionAmpliada'];
        $estPre = $itemRow['estPre'];
        $estPos = $itemRow['estPos'];

        $html .= '<tr>';
        $html .= '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
        $html .= "<td>$descripcion</td>";
        $html .= "<td>$descripcionAmpliada</td>";
        $html .= "<td>$estPre</td>";
        $html .= "<td>$estPos</td>";
        $html .= '<td><input type="number" name="material[id: ' . $id . ',]"></td>';
        $html .= '</tr>';
    }
    // Devolver el HTML generado
    echo $html;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}