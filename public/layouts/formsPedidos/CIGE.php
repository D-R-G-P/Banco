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
?>

<head>
    <link rel="stylesheet" type="text/css" href="/Banco/app/modules/select2/select2.min.css">

    <script src="/Banco/node_modules/jquery/dist/jquery.min.js"></script>

    <script src="/Banco/app/modules/select2/select2.min.js"></script>
</head>

<style>
    
    .solicitud {
        margin-top: 3vh;
        width: 100%;
        display: flex;
    justify-content: center;
    }

    form {
        width: 80%;
        border: .2vw #6d6d6d solid;
        padding: 1vw;
    }

    .cabecera {
        height: 15vh;
        display: flex;
        flex-direction: row;
        align-content: center;
        justify-content: space-around;
        align-items: center;
    }

    .cabecera img {
        height: 15vh;
        width: auto;
    }

    .cabecera h1 {
        text-decoration: underline;
    }

    .datosPaciente {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
    }

    input,
    .material,
    table {
        width: 100%;
        margin: 1vw 0;
    }

    input[name="tipoDocumento"] {
        width: auto;
    }

    div.inputRadio {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        margin-right: .5vw;
    }

    div.inputRadio label {
        margin-right: .3vw;
    }
</style>

<div class="solicitud">
    <form action="/Banco/app/realizarPedido/forms/cige.php" method="post" id="pedidoForm">
        <div class="datosPaciente">
            <div>
                <label for="paciente">Nombre y apellido del/la paciente:</label>
                <input type="text" name="paciente" id="paciente" required>
            </div>

            <div>
                <label for="edad">Edad:</label>
                <input type="number" name="edad" id="edad" required>
            </div>

            <div>
                <p>Tipo de documento:</p>
                <div id="tipoDocumento" style="display: flex;">
                    <div style="width: fit-content;">
                        <div class="inputRadio">
                            <label for="le">L.E.</label>
                            <input required type="radio" name="tipoDocumento" value="L.E." id="le">
                        </div>

                        <div class="inputRadio">
                            <label for="lc">L.C.</label>
                            <input required type="radio" name="tipoDocumento" value="L.C." id="lc">
                        </div>
                    </div>
                    <div style="width: fit-content;">
                        <div class="inputRadio">
                            <label for="ci">C.I.</label>
                            <input required type="radio" name="tipoDocumento" value="C.I." id="ci">
                        </div>

                        <div class="inputRadio">
                            <label for="dni">D.N.I.</label>
                            <input required type="radio" name="tipoDocumento" value="D.N.I." id="dni">
                        </div>
                    </div>
                    <div style="width: fit-content;">
                        <div class="inputRadio">
                            <label for="otro">Otro</label>
                            <input required type="radio" name="tipoDocumento" value="Otro" id="otro">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="documento">Documento:</label>
                <input type="text" name="documento" id="documento" required>
            </div>

            <div>
                <label for="direccion">Domicilio:</label>
                <input required type="text" id="direccion" name="direccion">
            </div>

            <div>
                <label for="telefono">Telefono de contacto (paciente):</label>
                <input required type="text" id="telefono" name="telefono">
            </div>
        </div>
        <div class="datosCirugia" style="width: 100%;">
            <div style="width: 100%;">
                <label for="controlBuscador">Diagnostico:</label>

                <select id="controlBuscador" style="width: 100%;" required>
                    <option value="" selected disabled>Seleccionar una opción</option>
                    <?php
                    try {
                        $stmt = $pdo->prepare("SELECT clave, descripcion FROM categoriascie10");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $clave = $row['clave'];
                            $descripcion = $row['descripcion'];
                            echo '<option value="' . $clave . '">' . $clave . ' - ' . $descripcion . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </select>

                <label for="controlBuscadorSecond">Nomenclador y cirugía:</label>

                <select id="controlBuscadorSecond" style="width: 100%;" required>
                    <option value="" selected disabled>Seleccionar una opción</option>
                    <?php
                    try {
                        $stmt = $pdo->prepare("SELECT codigo, descripcion FROM nomencladorescx");
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $codigo = $row['codigo'];
                            $descripcion = $row['descripcion'];
                            echo '<option value="' . $codigo . '">' . $codigo . ' - ' . $descripcion . '</option>';
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="material">
            <table>
                <thead>
                    <tr>
                        <th style="vertical-align: middle;">Item</th>
                        <th style="vertical-align: middle;">Nombre</th>
                        <th style="vertical-align: middle;">Descripcion corta</th>
                        <th style="vertical-align: middle;">Descripcion adicional</th>
                        <th style="vertical-align: middle;">Cantidad a solicitar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->prepare("SELECT item, nombre, d_corta, d_larga FROM items WHERE banco = 'CIGE' ORDER BY item ASC;");
                        $stmt->execute();

                        $itemsArray = []; // Arreglo para almacenar los items con cantidad mayor o igual a 1


                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $item = $row['item'];
                            $nombre = $row['nombre'];
                            $d_corta = $row['d_corta'];
                            $d_larga = $row['d_larga'];

                            echo '<tr>';
                            echo '<td style="text-align: center; vertical-align: middle;">' . $item . '</td>';
                            echo '<td style="vertical-align: middle;">' . $nombre . '</td>';
                            echo '<td style="vertical-align: middle;">' . $d_corta . '</td>';
                            echo '<td style="vertical-align: middle;">' . $d_larga . '</td>';
                            echo '<td style="vertical-align: middle;"><input type="number" name="material[' . $item . ']" min="0"></td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo 'Error: ' . $e->getMessage();
                    }
                    ?>

                </tbody>
            </table>

            <p style="margin-top: 3vw; width: 100%; display: flex; justify-content: flex-end;"> <b>Firma: <?php echo $user->getApellido() . " " . $user->getNombre(); ?></b> </p>
            <input type="hidden" value="<?php echo $user->getDni(); ?>">
            <input type="hidden" value="CIGE">

            <div style="margin-top: 3vw; width: 100%; display: flex; justify-content: flex-end;">
                <button type="button" onclick="enviarFormulario()" class="btn-verde"><i class="fa-solid fa-pen"></i> Solicitar y firmar</button>
            </div>
        </div>
    </form>
</div>