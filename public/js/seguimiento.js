// Obtener referencia al elemento select y al contenedor de las tablas
const bancoSelect = document.getElementById('bancoSelect');
const formContainer = document.getElementById('formContainer');
const nulo = document.getElementById('nulo');
const fondoArchive = document.getElementById('fondoArchive');

document.addEventListener('DOMContentLoaded', function () {
    checkForNewData(); // Llamar a la función cuando la página cargue
    listSelect();
});

// Función para actualizar las tablas según el banco seleccionado
function actualizarFormulario() {
    const idBanco = bancoSelect.value;

    // Realizar una solicitud al servidor para obtener las categorías y los datos de las tablas actualizados
    fetch(`/Banco/app/config/actualizar_formulario.php?idBanco=${idBanco}`)
        .then(response => response.text())
        .then(html => {
            // Actualizar el contenido del contenedor de las tablas con el HTML obtenido
            formContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function listSelect() {
    const selectedBanco = bancoSelect.value;

    if (selectedBanco !== '') {
        // Realizar una solicitud AJAX al servidor para obtener las solicitudes filtradas
        $.ajax({
            type: 'POST',
            url: '../../app/seguimiento/getList.php',
            data: {
                banco: selectedBanco
            },
            success: function (data) {
                // Actualizar el contenido de tbody con las nuevas solicitudes
                $('tbody').html(data);
            },
            error: function (error) {
                console.log(error);
            }
        });
    } else {
        // Limpiar el contenido de tbody y mostrar el mensaje "Seleccione un banco"
        $('tbody').html('<tr id="nulo">' +
            '<td colspan="9" style="text-align: center;"><b style="font-size: 2vw;">Seleccione un banco de la lista para continuar</b></td>' +
            '</tr>');
    }
}

// Manejar el evento change del select para filtrar las solicitudes
bancoSelect.addEventListener('change', function () {
    listSelect();
});

// Función para verificar nuevas solicitudes cada 15 segundos
function checkForNewData() {
    $.ajax({
        url: '../../app/config/checkNews.php',
        method: 'GET',
        success: function (response) {
            if (response === 'new') {
                // Ejecutar la función de JavaScript
                newAct();
            } else {
                newDesact();
            }
        },
        error: function (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });
}

function newAct() {
    const newA = document.getElementById('newA');
    newA.classList.add('newAct');
}

function newDesact() {
    const newA = document.getElementById('newA');
    newA.classList.remove('newAct');
}

setInterval(checkForNewData, 15000); // Ejecutar cada 15 segundos

function dialogoArchivo(id, GDEBA, paciente, dni) {
    // Actualizar los datos del expediente en el diálogo
    document.getElementById('expedienteTexto').innerText = GDEBA;
    document.getElementById('nombrePacienteTexto').innerText = paciente;
    document.getElementById('dniTexto').innerText = dni;
    document.getElementById('archivarBTN').href = `/Banco/app/seguimiento/archivar_solicitud.php?solicitudId=${id}`;

    // Mostrar el fondo del diálogo
    fondoArchive.style.display = 'flex';
}

function cerrarDialogoArchivo() {
    // Ocultar el fondo del diálogo
    fondoArchive.style.display = 'none';
}
