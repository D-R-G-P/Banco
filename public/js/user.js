$(document).ready(function () {
    // Añadir evento de clic al botón de búsqueda
    $("#btnBuscar").on("click", function () {
        // Capturar el valor del campo de búsqueda
        var dni = $("#campoBusqueda").val();
        var tablaBase = document.getElementById("tablaBase");

        // Realizar la solicitud AJAX
        $.ajax({
            url: "../../app/search/userSearch.php", // Reemplaza con la ruta correcta a tu script PHP
            type: "POST",
            data: { dni: dni },
            success: function (response) {
                // Verificar si la respuesta está vacía
                if (response.trim() === "") {
                    // Si la respuesta está vacía, mostrar un mensaje en la tabla
                    $("#resultadoTabla").html(
                        '<tr><td colspan="9">No se encontraron resultados.</td></tr>'
                    );
                } else {
                    // Si hay resultados, actualizar la tabla con los resultados de la búsqueda
                    $("#resultadoTabla").html(response);
                }
                tablaBase.style.display = "none";
            },
            error: function (error) {
                console.error("Error en la solicitud AJAX:", error);
            }
        });
    });
});









function dialogoArchivo(id, nombre, apellido, dni, username) {
    // Actualizar los datos del expediente en el diálogo
    document.getElementById('borrarNombre').innerText = apellido + " " + nombre;
    document.getElementById('borrarDni').innerText = dni;
    document.getElementById('borrarUsername').innerText = username;
    document.getElementById('borrarBTN').href = '/Banco/app/search/user_delete.php?userId=' + id;

    // Mostrar el fondo del diálogo
    fondoArchive.style.display = 'flex';
}

function cerrarDialogoArchivo() {
    // Ocultar el fondo del diálogo
    fondoArchive.style.display = 'none';
}