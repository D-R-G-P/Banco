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

$barcode = $_GET['codebar'];

$stmt = $pdo->prepare("SELECT item, nombre, categoria, banco FROM items WHERE barcode = :barcode");
$stmt->bindParam(':barcode', $barcode);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$item = $row['item'];
$nombre = $row['nombre'];
$categoria = $row['categoria'];
$banco = $row['banco'];

?>



<!DOCTYPE html>
<html lang="es-AR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCS - Modificando stock</title>

    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/modificar.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>

    <article>
        <div>
            <label for="barcode">Código de barras</label>
            <input type="text" name="barcode" disabled value="<?php echo isset($barcode) ? $barcode : ''; ?>">

            <label for="item">Item</label>
            <input type="text" name="item" disabled value="<?php echo isset($item) ? $item : ''; ?>">

            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" disabled value="<?php echo isset($nombre) ? $nombre : ''; ?>">

            <label for="categoria">Categoría</label>
            <input type="text" name="categoria" disabled value="<?php echo isset($categoria) ? $categoria : ''; ?>">

            <label for="banco">Banco</label>
            <input type="text" name="banco" disabled value="<?php echo isset($banco) ? $banco : ''; ?>">

            <div class="btn">
                <button class="btn-verde" onclick="openAdd()"><i class="fa-solid fa-plus"></i> Agregar stock</button>

                <button class="btn-rojo" onclick="openRemove()"><i class="fa-solid fa-minus"></i> Eliminar stock</button>
            </div>
        </div>

        <script>
            function openRemove() {
                if (remove.style.display == "none") {
                    remove.style.display = "flex";
                    add.style.display = "none";
                } else {
                    add.style.display = "none"
                    remove.style.display = "none";
                }
            }

            function openAdd() {
                if (add.style.display == "none") {
                    add.style.display = "flex";
                    remove.style.display = "none";
                } else {
                    add.style.display = "none"
                    remove.style.display = "none";
                }
            }
        </script>

        <div>
            <div class="add modificationDiv" id="add" style="display: none;">
                <form action="/Banco/app/modificarStock/anadirForm.php" method="post">
                    <input type="hidden" name="codebar" value="<?php echo isset($barcode) ? $barcode : ''; ?>">

                    <label for="stock">Stock a añadir</label>
                    <input name="stock" id="stock" type="number" min="1" value="1" required>

                    <label for="lote">Lote</label>
                    <input type="text" name="lote" required>

                    <button type="submit" class="btn-verde"><i class="fa-solid fa-plus"></i> Agregar stock</button>
                </form>
            </div>
            <div class="delete modificationDiv" id="remove" style="display: none;">
                <form action="/Banco/app/modificarStock/removeForm.php" method="post">
                    <input type="hidden" name="codebar" value="<?php echo isset($barcode) ? $barcode : ''; ?>">

                    <label for="stock">Stock a eliminar</label>
                    <input name="stock" id="stock" type="number" min="1" value="1" required>

                    <label for="lote">Lote</label>
                    <input type="text" name="lote" required>

                    <button type="submit" class="btn-rojo"><i class="fa-solid fa-minus"></i> Eliminar stock</button>
                </form>
            </div>
        </div>
    </article>
</body>

</html>