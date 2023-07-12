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