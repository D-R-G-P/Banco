function toggleForm() {
    if (formCodebar.style.display == "none") {
        formCodebar.style.display = "flex";
        back.style.display = "flex";
    } else {
        formCodebar.style.display = "none"
        back.style.display = "none"
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

function openRemove() {
    if (remove.style.display == "none") {
        remove.style.display = "flex";
        add.style.display = "none";
    } else {
        add.style.display = "none"
        remove.style.display = "none";
    }
}

function toggleAdd() {
    if (agregarForm.style.display == "none") {
        agregarForm.style.display = "flex";
        back.style.display = "flex";
    } else {
        agregarForm.style.display = "none";
        back.style.display = "none";
    }
}

function cargarCategorias() {
    var selectBanco = document.getElementById("banco");
    var selectCategoria = document.getElementById("categoria");
    var siglasSeleccionadas = selectBanco.value;

    // Limpiar las opciones anteriores
    selectCategoria.innerHTML = '<option value="" disabled selected></option>';

    // Obtener las categorías relacionadas con el banco seleccionado
    if (siglasSeleccionadas) {
        fetch("/Banco/app/modificarStock/obtener_categorias.php?siglas=" + siglasSeleccionadas)
            .then(response => response.json())
            .then(data => {
                data.forEach(categoria => {
                    var option = document.createElement('option');
                    option.text = categoria;
                    option.value = categoria;
                    selectCategoria.add(option);
                });
            });
    }
}