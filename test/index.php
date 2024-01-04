<?php include("db.php"); ?>

<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    
</head>
<body>
    <form action="codigo.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="dato" value="inserta_archivo">
        <div class="form-group">
            <label for="titulo">Titulo:</label>
            <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingrese el titulo">
        </div>
        <div class="form-group">
            <input type="file" class="form-control" name="imagen[]" id="imagenFileMultiple" multiple accept="image/*">
            <div class="file-list" id="fileList"></div>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>

    <div class="container mt-5 mb-5">
        <div class="row">
            <?php
            
            $sql = "SELECT titulo, archivo FROM test.test2";
            $resultado = $conexion->query($sql);

            if ($resultado->num_rows > 0) {
                while($fila = $resultado->fetch_assoc()) {
                    $titulo = $fila["titulo"];
                    $imageBase64 = $fila['archivo'];
                
            
            
            ?>

            <div class="col-md-3">
                <div class="card">
                    <h3><?php echo $titulo ?></h3>
                    <img src="data:image/png;base64,<?php echo $imageBase64; ?>"  alt="Imagen">
                </div>
            </div>

            <?php
            
            }
        } else {
            echo "No se encontraron imágenes en la base de datos.";
        }
            
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>