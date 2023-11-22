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

