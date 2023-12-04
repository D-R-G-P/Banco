<?php
// tu_script_php_que_procesa_solicitudes.php

require_once '../db/db.php';

// Obtener el valor del banco seleccionado
if (isset($_POST['banco'])) {
    $selectedBanco = $_POST['banco'];

    // Conectar a la base de datos
    $db = new DB();
    $pdo = $db->connect();

    // Definir la consulta SQL para obtener las solicitudes filtradas por banco
    $solicitudesQuery = "SELECT id, solicitud, tipo_solicitud, DATE_FORMAT(fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud, GDEBA, paciente, dni, estado, tipo_cirugia, intervencion, banco FROM solicitudes WHERE intervencion = 'si' AND banco = :selectedBanco ORDER BY id ASC";

    // Preparar y ejecutar la consulta para obtener las solicitudes filtradas por banco
    $solicitudesStatement = $pdo->prepare($solicitudesQuery);
    $solicitudesStatement->bindParam(':selectedBanco', $selectedBanco, PDO::PARAM_STR);
    $solicitudesStatement->execute();
    $solicitudes = $solicitudesStatement->fetchAll(PDO::FETCH_ASSOC);

    // Construir el HTML de las filas de la tabla
    $output = '';
    foreach ($solicitudes as $solicitud) {
        $output .= "<tr class='tablaPedidos'>";
        $output .= "<td>{$solicitud['solicitud']}</td>";
        $output .= "<td>{$solicitud['tipo_solicitud']}</td>";
        $output .= "<td>{$solicitud['fecha_solicitud']}</td>";
        $output .= "<td>{$solicitud['GDEBA']}</td>";
        $output .= "<td>{$solicitud['paciente']}</td>";
        $output .= "<td>{$solicitud['dni']}</td>";
        $output .= "<td>{$solicitud['estado']}</td>";
        $output .= "<td>{$solicitud['tipo_cirugia']}</td>";
        $output .= "<td style='display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: space-evenly; align-items: center;'>";
        $output .= "<a class='btn-verde' href='/Banco/app/seguimiento/tramitar.php?idSol={$solicitud['id']}'><i class='fa-solid fa-hand-pointer'></i></a>";
        $output .= '<button class="btn-verde" onclick="dialogoArchivo(\'' . $solicitud["id"] . '\', \'' . $solicitud["GDEBA"] . '\', \'' . $solicitud["paciente"] . '\', \'' . $solicitud["dni"] . '\')"><i class="fa-solid fa-box-archive"></i></button>';
        $output .= "</td>";
        $output .= "</tr>";
    }

    // Devolver el HTML construido
    echo $output;
} else {
    // Si no se proporciona un valor de banco, devolver un mensaje vacío
    echo '';
}
?>