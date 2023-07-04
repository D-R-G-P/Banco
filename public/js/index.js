// Obtener referencia al elemento select y al contenedor de las tablas
const bancoSelect = document.getElementById('bancoSelect');
const tablasContainer = document.getElementById('tablasContainer');

// Función para actualizar las tablas según el banco seleccionado
function actualizarTablas() {
  const idBanco = bancoSelect.value;

  // Realizar una solicitud al servidor para obtener las categorías y los datos de las tablas actualizados
  fetch(`app/config/actualizar_tablas.php?idBanco=${idBanco}`)
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