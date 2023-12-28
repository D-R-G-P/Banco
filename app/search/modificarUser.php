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
    $stmt = $pdo->prepare("SELECT id, nombre, apellido, dni, username, cargo, tipo_usuario, banco FROM users WHERE id = :id");
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
        $username = $userData['username'];
        $cargo = $userData['cargo'];
        $bancos = $userData['banco'];
        $tipoUsuario = $userData['tipo_usuario'];

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

    <article>
        <div class="datosForm">
            <h2 class="datos-cabeza">Modificar datos del usuario</h2>
            <div class="datos-contenido">
                <form action="/Banco/app/search/modificar.php" method="post">

                <input type="hidden" name="idUser" value="<?php echo $idUsuario; ?>">

                    <label for="nombre">Nombres</label>
                    <input type="text" name="nombre" value="<?php echo $nombre; ?>" required>

                    <label for="apellido">Apellidos</label>
                    <input type="text" name="apellido" value="<?php echo $apellido; ?>" required>

                    <label for="dni">D.N.I.</label>
                    <input type="text" name="dni" value="<?php echo $dni; ?>" required>

                    <label for="username">Nombre de usuario</label>
                    <input type="text" name="username" value="<?php echo $username; ?>" required>

                    <label for="password">Contraseña</label>
                    <input type="text" name="password">

                    <label for="cargo">Cargo</label>
                    <input type="text" name="cargo" value="<?php echo $cargo; ?>" required>

                    <label for="banco">Banco</label>
                    <select name="banco" required>
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT id, banco, siglas FROM bancos");
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $id_banco = $row['id'];
                                $banco = $row['banco'];
                                $siglas = $row['siglas'];

                                if ($bancos == $siglas) {
                                    echo "<option value='$siglas' selected>$banco - $siglas</option>";
                                } else {
                                    echo "<option value='$siglas'>$banco - $siglas</option>";
                                }
                            }
                        } catch (PDOException $e) {
                            echo 'Error: ' . $e->getMessage();
                        }
                        ?>
                        <option value="Otro">Otro</option>
                    </select>

                    <label for="tipo_usuario">Tipo de usuario</label>
                    <select name="tipo_usuario" required>
                        <option value="" disabled selected>Seleccionar una opción</option>
                        <option value="SuperAdmin" <?php if ($tipoUsuario == "SuperAdmin") echo "selected"; ?>>SuperAdmin</option>
                        <option value="Admin" <?php if ($tipoUsuario == "Admin") echo "selected"; ?>>Admin</option>
                        <option value="Deposito" <?php if ($tipoUsuario == "Deposito") echo "selected"; ?>>Deposito</option>
                        <option value="Instrumentador" <?php if ($tipoUsuario == "Instrumentador") echo "selected"; ?>>Instrumentador</option>
                        <option value="Cirujano" <?php if ($tipoUsuario == "Cirujano") echo "selected"; ?>>Cirujano</option>
                    </select>

                    <button type="submit" class="btn-verde"><i class="fa-solid fa-pencil"></i> Modificar datos</button>
                </form>
            </div>
        </div>
    </article>
</body>

</html>