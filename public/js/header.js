document.addEventListener('click', function (e) {
    var items = document.getElementsByClassName("BORON");
    var userOptions = document.getElementById("userOptions");
    var flecha = document.getElementById("flecha");

    // Verificar si el elemento clicado tiene la clase "BORON"
    var hasBoronClass = false;
    for (var i = 0; i < items.length; i++) {
        if (e.target == items[i]) {
            hasBoronClass = true;
            break;
        }
    }

    // Agregar o eliminar los atributos según corresponda
    if (hasBoronClass) {
        userOptions.classList.toggle("active");
        flecha.classList.toggle("active");
    } else {
        userOptions.classList.remove('active');
        flecha.classList.remove('active');
    }
});


var idleTime = 0;

$(document).ready(function () {
  // Incrementa el contador de inactividad cada segundo
  var idleInterval = setInterval(timerIncrement, 1000); // 1 segundo

  // Reinicia el contador de inactividad cuando el usuario realiza una acción
  $(this).mousemove(function (e) {
    idleTime = 0;
  });

  $(this).keypress(function (e) {
    idleTime = 0;
  });
});

function timerIncrement() {
  idleTime++;
  // Cambiar el tiempo de inactividad a 300 segundos (5 minutos)
  if (idleTime >= 300) {
    // Cierra la sesión automáticamente
    window.location.href = "/Banco/app/db/logout.php";
  }
}