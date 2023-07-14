document.getElementById("busqueda").addEventListener("input", function () {
    var input = this.value.toLowerCase();
    var select = document.getElementById("opciones");
    var options = select.getElementsByTagName("option");

    for (var i = 0; i < options.length; i++) {
        var optionText = options[i].text.toLowerCase();
        var optionValue = options[i].value.toLowerCase();
        var matchText = optionText.indexOf(input) > -1;
        var matchValue = optionValue.indexOf(input) > -1;

        if (matchText || matchValue) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
});

<input type="text" id="busqueda" placeholder="Buscar y seleccionar...">
                        <select id="opciones" required style="width: 75%;">
                            <option value="" selected disabled>Seleccione una opción</option>
                            <?php
                            try {
                                $stmt = $pdo->prepare("SELECT clave, descripcion FROM categoriascie10");
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $clave = $row['clave'];
                                    $descripcion = $row['descripcion'];

                                    echo '<option value="' . $clave . ' - ' . $descripcion . '">' . $clave . ' - ' . $descripcion . '</option>';
                                }
                            } catch (PDOException $e) {
                                echo 'Error: ' . $e->getMessage();
                            }
                            ?>
                        </select>