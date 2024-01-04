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
    $query = "SELECT id, DATE_FORMAT(fecha_solicitud, '%d/%m/%Y') AS fecha_solicitud, items_JSON, paciente, dni, categoriascie, domicilio, localidad, sexo, edad, tipoDoc, firmante FROM solicitudes WHERE id = :idSol";

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
        $categoriascie = $result['categoriascie'];

        $domicilio = $result['domicilio'];
        $localidad = $result['localidad'];
        $sexo = $result['sexo'];
        $edad = $result['edad'];
        $tipoDoc = $result['tipoDoc'];

        $firmante = $result['firmante'];
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
}


?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.C.S. - S.A.M.O.</title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/samo.css">
    <!-- <link rel="stylesheet" href="/Banco/public/css/table.css"> -->

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
    <article>
        <div class="one">
            <img style="margin-left: 5vw; height: 12vw;" src="/Banco/public/image/hsmlogobn.png">
            <h1>Orden de prestación</h1>
            <img style="margin-right: 5vw;" src="/Banco/public/image/SAMO.jpg">
        </div>

        <table>
            <tr>
                <td colspan="7">
                    <p>1. Establecimiento</p>
                    <b>H.I.G.A. GRAL SAN MARTÍN - LA PLATA</b>
                </td>
                <td>
                    <p>1.1 Código</p>
                    <b>44101304</b>
                </td>
            </tr>

            <tr>
                <td colspan="7">
                    <p>2. Apellido y nombre</p>
                    <b><?php echo $paciente ?></b>
                </td>
                <td>
                    <p>3. Fecha</p>
                    <b><?php echo $fecha_solicitud ?></b>
                </td>
            </tr>

            <tr>
                <td colspan="5" style="border-right: none;">
                    <p>2.1 Domicilio</p>
                    <b><?php echo $domicilio ?></b>
                </td>
                <td colspan="3" style="border-left: none;">
                    <p>Localidad</p>
                    <b><?php echo $localidad ?></b>
                </td>
            </tr>

            <tr>
                <td>
                    <p>4. Sexo</p>
                    <b><?php echo $sexo ?></b>
                </td>
                <td>
                    <p>5. Edad</p>
                    <b><?php echo $edad ?></b>
                </td>
                <td>
                    <p>6. N° documento</p>
                    <b><?php echo $dni ?></b>
                </td>
                <td>
                    <p>7. Tipo doc.</p>
                    <b><?php echo $tipoDoc ?></b>
                </td>
                <td>
                    <p>8. C.E.</p>
                    <b></b>
                </td>
                <td>
                    <p>9. Sala</p>
                    <b></b>
                </td>
                <td>
                    <p>10. Cama</p>
                    <b></b>
                </td>
                <td>
                    <p>11. H. clínica</p>
                    <b></b>
                </td>
            </tr>

            <tr>
                <td>
                    <p>12. Condición</p>
                    <b>B</b>
                </td>
                <td colspan="4">
                    <p>13. Obra social</p>
                    <b>-</b>
                </td>
                <td colspan="2">
                    <p>14. Tipo afil.</p>
                    <b></b>
                </td>
                <td>
                    <p>15. N° afiliado</p>
                    <b></b>
                </td>
            </tr>

            <tr>
                <td colspan="7">
                    <p>16. Diagnóstico clínico</p>
                    <b><?php echo $categoriascie ?></b>
                </td>
                <td>
                    <p>17. Código</p>
                    <b></b>
                </td>
            </tr>

            <tr style="height: 30vw;">
                <td>
                    <p>18. Código</p>
                    <b></b>
                </td>
                <td colspan="5">
                    <p>19. Concepto</p>
                    <b style="font-size: 1vw;">
                        <?php

                        // Decodificar el JSON
                        $items_array = json_decode($items_JSON, true);

                        $html = '';

                        // Recorrer cada elemento del array
                        foreach ($items_array as $item) {
                            // Obtener el ID y la cantidad del array
                            $id = $item['id'];
                            $cantidad = $item['cantidad'];

                            // Realizar la consulta para obtener la información del item
                            $query = "SELECT item, nombre FROM items WHERE id = :id";

                            $stmt = $pdo->prepare($query);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt->execute();

                            // Obtener el resultado de la consulta
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);

                            // Verificar si se encontró la información
                            if ($result) {
                                // Extraer la información
                                $item = $result['item'];
                                $nombre = $result['nombre'];

                                // Agregar la fila a la tabla HTML
                                $html .= "Item $item - $nombre - Cantidad: $cantidad";
                            } else {
                                // Manejar el caso donde no se encontró información para el ID
                                $html .= 'No se encontró información para el ID ' . $id . '';
                            }
                        }

                        // Mostrar la tabla HTML
                        echo $html;
                        ?>
                    </b>
                </td>
                <td>
                    <p>20. Unitario</p>
                    <b></b>
                </td>
                <td>
                    <p>21. Total</p>
                    <b></b>
                </td>
            </tr>

            <tr style="height: 6vw;">
                <td rowspan="2">
                    <p>22. Factura interv.</p>
                    <b></b>
                </td>
                <td rowspan="3" colspan="5">
                    <p>23. Firma, sello y matrícula profesional</p>
                    <img src="data:image/png;base64,<?php echo $firma; ?>" alt="Firma" style="height: 20vw; width: auto;">
                </td>
                <td rowspan="3">
                    <p>25. Conforme afiliado</p>
                    <b></b>
                </td>
                <td>
                    <p>24. Total</p>
                    <b></b>
                </td>
            </tr>
            <tr style="height: 6vw;">
                <td>
                    <p>26. Abonar por Af.</p>
                    <b></b>
                </td>
            </tr>
            <tr style="height: 6vw;">
                <td></td> <!-- Espacio en blanco abajo de Factura interv. -->
                <td>
                    <p>26.1 Abonar por OS</p>
                    <b></b>
                </td>
            </tr>
            <tr style="height: 17vw;">
                <td colspan="4">
                    <p>27. Autorización OS - Internación o práctica</p>
                    <img style="width: auto; height: 15vw; margin-left: 14vw;" src="/Banco/public/image/sellohsm.png">
                </td>
                <td colspan="4">
                    <p>28. Práctica realizada por</p>
                    <b></b>
                </td>
            </tr>
        </table>
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