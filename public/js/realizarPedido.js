// Obtener referencia al elemento select y al contenedor de las tablas
const bancoSelect = document.getElementById('bancoSelect');
const tablasContainer = document.getElementById('tabla-resultados');

// Función para actualizar las tablas según el banco seleccionado
function actualizarTablas() {
    const idBanco = bancoSelect.value;

    // Realizar una solicitud al servidor para obtener las categorías y los datos de las tablas actualizados
    fetch(`../../app/solicitable/getTable.php?idBanco=${idBanco}`)
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
        fetch('/Banco/app/solicitable/getTable.php?idBanco=' + selectedBanco)
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
                    newRow.insertCell(1).appendChild(document.createTextNode(row.descripcion));
                    newRow.insertCell(2).appendChild(document.createTextNode(row.descripcionAmpliada));
                    newRow.insertCell(3).appendChild(document.createTextNode(row.estPre));
                    newRow.insertCell(4).appendChild(document.createTextNode(row.estPos));
                    var inputCell = newRow.insertCell(5);
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