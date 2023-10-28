<?php

// Verificar si se ha proporcionado un código de barras en la URL
if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];

    // Realizar la conexión a la base de datos
    require_once '../../app/db/db.php';
    $db = new DB();
    $pdo = $db->connect();

    try {
        // Consultar la información de los elementos basada en el código de barras
        $stmt = $pdo->prepare("SELECT id, item, nombre, categoria, banco, d_corta, d_larga, estudios FROM items WHERE barcode = :barcode");
        $stmt->bindParam(':barcode', $barcode);
        $stmt->execute();

        // Obtener todos los elementos encontrados
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // Redirigir a alguna página de error si no se proporciona el código de barras
    header('Location: error.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="es-AR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.C.S. - Modificar stock</title>

    <link rel="shortcut icon" href="/Banco/public/image/logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="/Banco/public/css/base.css">
    <link rel="stylesheet" href="/Banco/public/css/header.css">
    <link rel="stylesheet" href="/Banco/public/css/select_item.css">

    <!-- FontAwesome -->
    <script src="/Banco/node_modules/@fortawesome/fontawesome-free/js/all.js"></script>
</head>

<body>
    <article>
        <h1>Se han encontrado varios elementos coincidentes, selecciona el correcto debajo</h1>
        <?php foreach ($items as $item) : ?>
            <div class="capsula">
                <div class="content">
                    <div class="titulo">Item <?php echo $item['item'] . ' - ' . $item['nombre']; ?>
                    </div>
                    <div class="cuerpo">
                        <div>Codigo de barras: <?php echo $barcode; ?></div>
                        <div>Descripción corta: <?php echo $item['d_corta']; ?></div>
                        <div>Descripción larga: <?php echo $item['d_larga']; ?></div>
                        <div>Estudios: <?php echo $item['estudios']; ?></div>
                        <div>Categoria: <?php echo $item['categoria']; ?></div>
                        <div>Banco: <?php echo $item['banco']; ?></div>
                    </div>
                </div>
                <a href="/Banco/app/modificarStock/modificar.php?id=<?php echo $item['id']; ?>">
                    <button class="btn-verde" style="display: flex; flex-direction: row; flex-wrap: nowrap; align-items: center;">
                        <i class="fa-solid fa-circle-check" style="font-size: 1.5vw; margin-right: .4vw;"></i> Seleccionar item
                    </button>
                </a>
            </div>
        <?php endforeach; ?>
    </article>
</body>

</html>