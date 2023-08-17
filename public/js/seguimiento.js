// Obtener referencia al elemento select y al contenedor de las tablas
const bancoSelect = document.getElementById('bancoSelect');
const formContainerr = document.getElementById('formContainer');

// Función para actualizar las tablas según el banco seleccionado
function actualizarFormulario() {
    const idBanco = bancoSelect.value;

    // Realizar una solicitud al servidor para obtener las categorías y los datos de las tablas actualizados
    fetch(`/Banco/app/config/actualizar_formulario.php?idBanco=${idBanco}`)
        .then(response => response.text())
        .then(html => {
            // Actualizar el contenido del contenedor de las tablas con el HTML obtenido
            formContainerr.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Escuchar el evento de cambio en el elemento select
bancoSelect.addEventListener('change', actualizarFormulario);

// Obtener el elemento del select y el div del formulario
const selectElement = document.getElementById("bancoSelect");
const formContainer = document.getElementById("formContainer");

// Manejar el evento onchange del select
selectElement.addEventListener("change", () => {
    // Obtener el valor seleccionado
    const selectedValue = selectElement.value;

    // Actualizar el contenido del div según el valor seleccionado
    if (selectedValue === "CIGE") {
        // Realizar una solicitud al servidor para obtener el contenido de CIGE.php
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Insertar el contenido en el div correspondiente
                document.getElementById("contenidoDinamico").innerHTML = xhr.responseText;

                // Inicializar Select2 para los elementos cargados dinámicamente
                $('#controlBuscador').select2();
                $('#controlBuscadorSecond').select2();
            }
        };
        xhr.open("GET", "formsPedidos/CIGE.php", true);
        xhr.send();
    } else if (selectedValue === "OTRA_OPCION") {
        formContainer.innerHTML = "Contenido específico para otra opción";
    } else {
        // Contenido predeterminado si no se selecciona ninguna opción válida
        formContainer.innerHTML = "Contenido predeterminado";
    }
});
$(document).ready(function () {
    $('#controlBuscador').select2();
    $('#controlBuscadorSecond').select2();
});

document.addEventListener("DOMContentLoaded", function() {
    checkForNewData(); // Llamar a la función cuando la página cargue
});

function checkForNewData() {
    $.ajax({
        url: '../../app/config/checkNews.php', // Asegúrate de ajustar la ruta
        method: 'GET',
        success: function(response) {
            if (response === 'new') {
                // Ejecutar la función de JavaScript
                newAct();
            } else {
                newDesact();
            }
        }
    });
}

function newAct() {
    newA.classList.add('newAct');
}

function newDesact() {
    newA.classList.remove('newAct');
}

setInterval(checkForNewData, 15000); // Ejecutar cada 15 segundos