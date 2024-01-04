<?php

require_once '../../../app/db/user_session.php';
require_once '../../../app/db/user.php';
require_once '../../../app/db/db.php';

$user = new User();
$userSession = new UserSession();
$currentUser = $userSession->getCurrentUser();
$user->setUser($currentUser);

$db = new DB();
$pdo = $db->connect();

// Verificar si se ha enviado un ID válido a través de la URL
if (isset($_GET['idSol']) && is_numeric($_GET['idSol'])) {
    $idSol = $_GET['idSol'];

    // Consulta SQL para obtener el ID y el estado actual del item
    $query = "SELECT id, DATE_FORMAT(fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud, GDEBA, items_JSON, paciente, dni, nomencladores, categoriascie, firmante FROM solicitudes WHERE id = :idSol";

    // Preparar la sentencia
    $statement = $pdo->prepare($query);

    // Bind del valor del ID
    $statement->bindParam(':idSol', $idSol, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($statement->execute()) {
        // Obtener el resultado de la consulta
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $fecha_solicitud = $result['fecha_solicitud'];
        $items_JSON = $result['items_JSON'];
        $paciente = $result['paciente'];
        $dni = $result['dni'];
        $GDEBA = $result['GDEBA'];
        $nomencladores = $result['nomencladores'];
        $categoriascie = $result['categoriascie'];
        $firmante = $result['firmante'];
    }
}

$firmQuery = "SELECT matricula, ref, firma FROM users WHERE matricula = :matricula";

try {
    $firmStatement = $pdo->prepare($firmQuery);

    if (!$firmStatement) {
        throw new PDOException("Error al preparar la consulta.");
    }

    $firmStatement->bindParam(':matricula', $firmante, PDO::PARAM_INT);

    if (!$firmStatement->execute()) {
        throw new PDOException("Error al ejecutar la consulta.");
    }

    $firmResult = $firmStatement->fetch(PDO::FETCH_ASSOC);

    if ($firmResult === false) {
        // La consulta no devolvió resultados
        throw new PDOException("No se encontraron resultados para la matrícula proporcionada.");
    }

    $ref = $firmResult['ref'];
    $firma = $firmResult['firma'];
} catch (PDOException $e) {
    // Manejar el error (puedes imprimir el mensaje de error o realizar alguna otra acción)
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.C.S. - Prescipcion</title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/prescripcion.css">
    <link rel="stylesheet" href="/Banco/public/css/table.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
    <article>
        <div class="cabecera">
            <img src="/Banco/public/image/hsmlogo.png" width="auto" height="100vw">
            <div class="text">
                <h1>Solicitud de autorización para la compra de prótesis</h1>
                <p>Expediente GDEBA: <u><?php echo $GDEBA ?></u></p>
            </div>
        </div>
        <div class="datos" style="width: 90%;">
            <div class="first">
                <p>Paciente: <?php echo $paciente ?></p>
                <p>Fecha: <?php echo $fecha_solicitud ?></p>
            </div>
            <p>DNI: <?php echo $dni ?></p>

            <p style="margin-top: 1.7vw;">Diagnostico del paciente:</p>
            <p style="text-align: center;"><?php echo $categoriascie ?></p>

            <p style="margin-top: 1.7vw;">Estrategia quirurgica prevista:</p>
            <p style="text-align: center;"><?php echo $nomencladores ?></p>
        </div>
        <div class="tabla">
            <table style="margin-top: .8vw;">
                <?php

                // Decodificar el JSON
                $items_array = json_decode($items_JSON, true);

                // Cabecera de la tabla HTML
                $html = '<thead>
            <tr>
              <th>Item</th>
              <th>Descripción</th>
              <th>Descripcion alternativa</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>';

                // Recorrer cada elemento del array
                foreach ($items_array as $item) {
                    // Obtener el ID y la cantidad del array
                    $id = $item['id'];
                    $cantidad = $item['cantidad'];

                    // Realizar la consulta para obtener la información del item
                    $query = "SELECT item, d_corta, d_larga FROM items WHERE id = :id";

                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    // Obtener el resultado de la consulta
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Verificar si se encontró la información
                    if ($result) {
                        // Extraer la información
                        $item = $result['item'];
                        $d_corta = $result['d_corta'];
                        $d_larga = $result['d_larga'];

                        // Agregar la fila a la tabla HTML
                        $html .= '<tr>';
                        $html .= '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
                        $html .= '<td>' . $d_corta . '</td>';
                        $html .= '<td>' . $d_larga . '</td>';
                        $html .= '<td style="text-align: center; vertical-align: middle;">' . $cantidad . '</td>';
                        $html .= '</tr>';
                    } else {
                        // Manejar el caso donde no se encontró información para el ID
                        $html .= '<tr>';
                        $html .= '<td colspan="4">No se encontró información para el ID ' . $id . '</td>';
                        $html .= '</tr>';
                    }
                }

                // Cierre de la tabla HTML
                $html .= '</tbody>';

                // Mostrar la tabla HTML
                echo $html;
                ?>


                </tbody>
            </table>

            <img src="data:image/png;base64,<?php echo $firma; ?>" alt="Firma" style="height: 23vw; width: auto; margin-top: 10vw;">
            <div class="sign" style="border-top: .2vw; border-left: 0; border-right: 0; border-bottom: 0; border-color: black; border-style: solid; width: 25vw; text-align: center;">
                <p>Medico tratante</p>
            </div>
        </div>
    </article>

    <script>
        // Función para imprimir y cerrar después de la impresión
        function imprimirYCerrar() {
            // Abrir la ventana de impresión
            window.print();

            // Detectar cuándo se completa la impresión
            window.addEventListener('afterprint', function() {
                // Cerrar la ventana después de imprimir
                window.close();
            });
        }

        // Llamar a la función cuando se cargue la página
        window.addEventListener('load', imprimirYCerrar);
    </script>
</body>

</html>