$(document).ready(function () {
    // Añadir evento de clic al botón de búsqueda
    $("#btnBuscar").on("click", function () {
        // Capturar el valor del campo de búsqueda
        var dni = $("#campoBusqueda").val();
        var tablaBase = document.getElementById("tablaBase");

        // Realizar la solicitud AJAX
        $.ajax({
            url: "../../app/search/archivoSearch.php", // Reemplaza con la ruta correcta a tu script PHP
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