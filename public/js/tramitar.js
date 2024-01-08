// Obtener y formatear el campo de DNI
function formatDNI(input) {
    // Obtener el valor del input y eliminar cualquier carácter no numérico
    var num = input.value.replace(/\D/g, '');

    // Si hay al menos un número
    if (num) {
        // Formatear el número con puntos
        var formattedNum = '';
        for (var i = 0; i < num.length; i++) {
            if (i > 0 && i % 3 === 0) {
                formattedNum = '.' + formattedNum;
            }
            formattedNum = num[num.length - 1 - i] + formattedNum;
        }

        // Establecer el valor formateado en el input
        input.value = formattedNum;
    } else {
        input.value = '';
    }
}

function panel() {
    var panel = document.getElementById('panel');
    var fatexto = document.getElementById('fa-texto');
    panel.classList.toggle('panel-abierto');
    fatexto.classList.toggle('fa-textopen');
}

// Función para manejar el cambio en el campo de entrada
function actualizarDatosPrescriptor() {
    var firmanteInput = document.getElementsByName("firmante")[0];
    var datosPrescriptorDiv = document.getElementById("datos-prescriptor");

    // Verificar si se seleccionó un firmante
    if (firmanteInput.value) {
        // Realizar una solicitud asíncrona al servidor para obtener los detalles del prescriptor
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Actualizar el contenido del segundo div con los datos del prescriptor
                datosPrescriptorDiv.innerHTML = xhr.responseText;
            }
        };

        // Enviar la solicitud al servidor
        xhr.open("GET", "obtenerDatosPrescriptor.php?firmante=" + firmanteInput.value, true);
        xhr.send();
    } else {
        // Si no se selecciona un firmante, restablecer los datos del prescriptor
        datosPrescriptorDiv.innerHTML = "Nombre completo: <br> Matricula: ";
    }
}

// Agregar un evento de cambio al campo de entrada
var firmanteInput = document.getElementsByName("firmante")[0];
firmanteInput.addEventListener("input", actualizarDatosPrescriptor);