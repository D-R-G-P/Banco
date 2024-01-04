<?php

    $servidor = "localhost";
    $usuario = "root";
    $password = "root";
    $nombreDB = "test";

$conexion = new mysqli($servidor, $usuario, $password, $nombreDB);

if($conexion->connect_error){
    die("Conexión fallida: " . $conexion->connect_error);
} else {
    
}

?>