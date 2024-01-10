<?php

require_once '../../app/db/user_session.php';
require_once '../../app/db/user.php';
require_once '../../app/db/db.php';

$user = new User();
$userSession = new UserSession();
$currentUser = $userSession->getCurrentUser();
$user->setUser($currentUser);

$db = new DB();
$pdo = $db->connect();

// Verificar si se ha enviado un ID válido a través de la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta SQL para obtener los datos del usuario por ID
    $stmt = $pdo->prepare("SELECT id, banco, item, descripcion, descripcionAmpliada, estPre, estPos FROM itemssolicitables WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados de la consulta
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontraron datos para el ID proporcionado
    if ($userData) {
        // Asignar valores a las variables para mostrar en el formulario
        $idItem = $userData['id'];
        $banco = $userData['banco'];
        $item = $userData['item'];
        $descripcion = $userData['descripcion'];
        $descripcionAmpliada = $userData['descripcionAmpliada'];
        $estPre = $userData['estPre'];
        $estPos = $userData['estPos'];
    } else {
        // Redirigir o mostrar un mensaje de error si no se encontraron datos
        echo "No se encontraron datos para el ID proporcionado";
        exit();
    }
} else {
    // Redirigir o mostrar un mensaje de error si no se proporcionó un ID válido
    echo "ID inválido o no proporcionado";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.C.S. - Editar item</title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/modificarItem.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
    <article>
        <div class="datosForm">
            <h2 class="datos-cabeza">Modificar datos del item</h2>

            <div class="datos-contenido">
                <form action="/Banco/app/solicitable/modificarForm.php" method="post">

                    <input type="hidden" name="idItem" value="<?php echo $idItem; ?>">

                    <label for="banco">Banco</label>
                    <select name="banco" required>
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $id_banco = $row['id'];
                                $bancoNew = $row['banco'];
                                $siglas = $row['siglas'];

                                // Agrega esto para depurar
                                echo "<option value='$siglas'";
                                if ($banco == $siglas) {
                                    echo " selected";
                                }
                                echo ">$bancoNew - $siglas</option>";
                            }
                        } catch (PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        }
                        ?>
                    </select>


                    <label for="item">Item</label>
                    <input type="number" name="item" value="<?php echo $item; ?>" required>

                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" required><?php echo $descripcion; ?></textarea>

                    <label for="descripcionAmpliada">Descripción ampliada</label>
                    <textarea name="descripcionAmpliada" required><?php echo $descripcionAmpliada; ?></textarea>

                    <label for="estPre">Estudios pre operatorios</label>
                    <textarea name="estPre" required><?php echo $estPre; ?></textarea>

                    <label for="estPos">Estudios pos operatorios</label>
                    <textarea name="estPos" required><?php echo $estPos; ?></textarea>


                    <button type="submit" class="btn-verde"><i class="fa-solid fa-pencil"></i> Modificar datos</button>
                </form>
            </div>
        </div>
    </article>
</body>

</html>