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
    // Consultar las siglas del banco en base al id proporcionado
    $siglasQuery = "SELECT siglas FROM bancos WHERE id = :idBanco";
    $siglasStatement = $pdo->prepare($siglasQuery);
    $siglasStatement->bindParam(':idBanco', $idBanco);
    $siglasStatement->execute();
    $siglas = $siglasStatement->fetchColumn();

    // Consultar el formulario en base a las siglas del banco
    $formularioQuery = "SELECT formulario FROM forms WHERE banco = :siglas";
    $formularioStatement = $pdo->prepare($formularioQuery);
    $formularioStatement->bindParam(':siglas', $siglas);
    $formularioStatement->execute();
    $formulario = $formularioStatement->fetchColumn();

    // Mostrar el formulario HTML
    echo $formulario;
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}