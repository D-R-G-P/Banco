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
    $stmt = $pdo->prepare("SELECT id, nombre, apellido, dni, cargo, matricula, firma FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener los resultados de la consulta
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontraron datos para el ID proporcionado
    if ($userData) {
        // Asignar valores a las variables para mostrar en el formulario
        $idUsuario = $userData['id'];
        $nombre = $userData['nombre'];
        $apellido = $userData['apellido'];
        $dni = $userData['dni'];
        $cargo = $userData['cargo'];
        $matricula = $userData['matricula'];
        $firma = $userData['firma'];
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
    <title>S.C.S. - Editar usuario</title>
    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/modificarUser.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>

    <article class="signDatos" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1vw; justify-items: center;
    align-items: stretch;">
        <div class="datosForm" style="width: 80%;">
            <h2 class="datos-cabeza">Añadir firma al usuario</h2>

            <div class="datos-contenido">
                <form action="/Banco/app/search/addSignForm.php" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="idUser" value="<?php echo $idUsuario; ?>">

                    <input type="hidden" name="titulo" value="Firma <?php echo $nombre . " " . $apellido ?>">

                    <label for="nombre">Nombres</label>
                    <input type="text" value="<?php echo $nombre; ?>" required readonly>

                    <label for="apellido">Apellidos</label>
                    <input type="text" value="<?php echo $apellido; ?>" required readonly>

                    <label for="dni">D.N.I.</label>
                    <input type="text" value="<?php echo $dni; ?>" required readonly>

                    <label for="cargo">Cargo</label>
                    <input type="text" name="cargo" value="<?php echo $cargo; ?>" required readonly>

                    <h2 class="datos-cabeza">Agregar firma</h2>

                    <input type="hidden" name="dato" value="inserta_archivo">

                    <input type="number" value="<?php echo $matricula; ?>" name="matricula" placeholder="Matricula (solo números)" style="margin-top: 1vw;" required>

                    <input type="file" name="imagen[]" id="imagen" accept="image/*" style="margin-top: 1vw;" required>

                    <button type="submit" class="btn-verde"><i class="fa-solid fa-pencil"></i> Modificar datos</button>
                </form>

            </div>
        </div>
        <?php if ($firma != "") { ?>
            <div class="datosForm" style="width: 80%;">
                <h2 class="datos-cabeza">Firma de usuario cargada</h2>

                <div class="datos-contenido" id="firm">

                    <img src="data:image/png;base64,<?php echo $firma; ?>" alt="Firma" class="firmacar">

                </div>
            </div>
        <?php } ?>
    </article>
</body>

</html>