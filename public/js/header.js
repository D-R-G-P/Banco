// Obtener referencias a los elementos del DOM
var perfilButton = document.getElementById('perfilButton');
var perfilMenu = document.getElementById('perfilMenu');
var iconoFlecha = document.getElementById('arrow');

// Agregar evento de clic al botón
perfilButton.addEventListener('click', function () {
  // Verificar si el menú tiene la clase "mostrar"
  var menuEstaVisible = perfilMenu.classList.contains('mostrar');

  if (menuEstaVisible) {
    // Si el menú está visible, ciérralo
    cerrarMenu();
  } else {
    // Si el menú no está visible, ábrelo
    abrirMenu();
  }
});

// Agregar evento de clic al documento para cerrar el menú si se hace clic fuera de él
document.addEventListener('click', function (event) {
  var isClickInside = perfilButton.contains(event.target) || perfilMenu.contains(event.target);
  if (!isClickInside) {
    // Si el clic no fue dentro del botón o del menú, cierra el menú
    cerrarMenu();
  }
});

// Función para abrir el menú
function abrirMenu() {
  perfilMenu.classList.add('mostrar')
  iconoFlecha.classList.add('rotar');
}

// Función para cerrar el menú
function cerrarMenu() {
  if (perfilMenu.classList.contains('mostrar')) {
    perfilMenu.classList.add('cerrar');
    setTimeout(cerrarTodo, 500);
  }
  perfilMenu.classList.remove('mostrar');
  iconoFlecha.classList.remove('rotar');
}

function cerrarTodo() {
  perfilMenu.classList.remove('cerrar');
}


// Funciones de inactividad
var idleTime = 0;

$(document).ready(function () {
  // Incrementa el contador de inactividad cada segundo
  var idleInterval = setInterval(timerIncrement, 1000); // 1 segundo

  // Reinicia el contador de inactividad cuando el usuario realiza una acción
  $(document).on('mousemove keypress', function () {
    idleTime = 0;
  });
});

function timerIncrement() {
  // Incrementar el tiempo de inactividad
  idleTime++;

  // Cambiar el tiempo de inactividad a 300 segundos (5 minutos)
  var maxIdleTime = 300;

  if (idleTime >= maxIdleTime) {
    // Cierra la sesión automáticamente
    window.location.href = "/Banco/app/db/logout.php";
  }
}