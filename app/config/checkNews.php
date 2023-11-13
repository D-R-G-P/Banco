<?php
require_once('../db/db.php');

$db = new DB();
$pdo = $db->connect();

function checkForNewRows() {
    global $pdo;
    
    $query = "SELECT COUNT(*) FROM solicitudes WHERE intervencion = 'no'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();
    
    return $rowCount > 0 ? true : false;
}

if (checkForNewRows()) {
    echo 'new';
}
?>