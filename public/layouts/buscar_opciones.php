<?php
require_once '../../app/db/db.php';

$db = new DB();
$pdo = $db->connect();

$searchTerm = $_POST['searchTerm'];

$sql = "SELECT clave, descripcion FROM categoriascie10";
if (!empty($searchTerm)) {
    $sql .= " WHERE clave LIKE :searchTerm OR descripcion LIKE :searchTerm";
}

$stmt = $pdo->prepare($sql);

if (!empty($searchTerm)) {
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bindParam(':searchTerm', $searchTerm);
}

$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Establecer la cabecera Content-Type para indicar que se envía JSON
header('Content-Type: application/json');

// Imprimir los resultados como JSON
echo json_encode($results);
?>