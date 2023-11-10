// Obtener referencia al elemento select y al contenedor de las tablas
const bancoSelect = document.getElementById('bancoSelect');
const tablasContainer = document.getElementById('tabla-resultados');

// Función para actualizar las tablas según el banco seleccionado
function actualizarTablas() {
    const idBanco = bancoSelect.value;

    // Realizar una solicitud al servidor para obtener las categorías y los datos de las tablas actualizados
    fetch(`gettable.php?idBanco=${idBanco}`)
        .then(response => response.text())
        .then(html => {
            // Actualizar el contenido del contenedor de las tablas con el HTML obtenido
            tablasContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Escuchar el evento de cambio en el elemento select
bancoSelect.addEventListener('change', actualizarTablas);

// Actualizar las tablas al cargar la página inicialmente
actualizarTablas();


// Obtener la fecha actual en el formato "dd/mm/yyyy" para el input date
var hoy = new Date();
var dd = String(hoy.getDate()).padStart(2, '0');
var mm = String(hoy.getMonth() + 1).padStart(2, '0'); // Enero es 0
var yyyy = hoy.getFullYear();

var fechaHoy = dd + '/' + mm + '/' + yyyy;
document.getElementById('fecha').setAttribute('max', yyyy + '-' + mm + '-' + dd);
document.getElementById('fecha').setAttribute('value', yyyy + '-' + mm + '-' + dd);


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

document.getElementById('banco').addEventListener('change', function () {
    var selectedBanco = this.value;
    if (selectedBanco) {
        fetch('/Banco/app/intervencion/gettable.php?banco=' + selectedBanco)
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                // Manipular los datos y actualizar la tabla aquí
                var tablaResultados = document.getElementById('tabla-resultados').getElementsByTagName('tbody')[0];
                tablaResultados.innerHTML = ''; // Limpiar la tabla antes de agregar nuevos datos
                data.forEach(function (row) {
                    var newRow = tablaResultados.insertRow(-1);
                    newRow.insertCell(0).appendChild(document.createTextNode(row.item));
                    newRow.insertCell(1).appendChild(document.createTextNode(row.nombre));
                    newRow.insertCell(2).appendChild(document.createTextNode(row.d_corta));
                    var inputCell = newRow.insertCell(3);
                    var input = document.createElement('input');
                    input.type = 'number';
                    inputCell.appendChild(input);
                });
            })
            .catch(function (error) {
                console.log('Error: ' + error);
            });
    }
});

function carga() {
    // Verificar si se ha seleccionado un banco
    if (bancoSelect.value !== "") {
        // Eliminar el contenido del contenedor de la tabla
        tablaCruda.remove();

        // Aquí puedes realizar cualquier otra acción que necesites al cambiar el banco
        // Por ejemplo, cargar una nueva tabla o realizar una solicitud al servidor
    }
}

// Agregar un evento change al elemento select
bancoSelect.addEventListener('change', carga);