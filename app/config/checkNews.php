<?php
require_once('../db/db.php');

$db = new DB();
$pdo = $db->connect();

function checkForNewRows() {
    global $pdo;
    
    $query = "SELECT COUNT(*) FROM cigeforms WHERE estado = 'new'";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();
    
    return $rowCount > 0 ? true : false;
}

if (checkForNewRows()) {
    echo 'new';
}
?>