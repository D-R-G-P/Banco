function enviarFormulario() {
        // Obtener las cantidades de materiales
        const itemsArray = [];
        const inputs = document.querySelectorAll('input[name^="material"]');
        inputs.forEach(input => {
            const item = input.name.split('[')[1].split(']')[0];
            const cantidad = input.value || 0;
            if (cantidad >= 1) {
                itemsArray.push({ "item": item, "cantidad": cantidad });
            }
        });

        // Crear el objeto JSON con la lista de materiales
        const jsonData = JSON.stringify(itemsArray);

        // Realizar la petición AJAX para enviar los datos al servidor
        fetch('/Banco/app/realizarPedido/forms/cige.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'paciente=' + encodeURIComponent(document.getElementById('paciente').value)
                    + '&edad=' + encodeURIComponent(document.getElementById('edad').value)
                    + '&tipoDocumento=' + encodeURIComponent(document.querySelector('input[name="tipoDocumento"]:checked').value)
                    + '&documento=' + encodeURIComponent(document.getElementById('documento').value)
                    + '&direccion=' + encodeURIComponent(document.getElementById('direccion').value)
                    + '&telefono=' + encodeURIComponent(document.getElementById('telefono').value)
                    + '&controlBuscador=' + encodeURIComponent(document.getElementById('controlBuscador').value)
                    + '&controlBuscadorSecond=' + encodeURIComponent(document.getElementById('controlBuscadorSecond').value)
                    + '&items_json=' + encodeURIComponent(jsonData)
            })
            .then(response => response.json())
            .then(data => {
                // Aquí puedes manejar la respuesta del servidor si es necesario
                console.log(data);
                locate.reload();
            })
            .catch(error => {
                // Aquí puedes manejar los errores si ocurren
                console.error(error);
            });
    }

    $(document).ready(function() {
        $('#controlBuscador').select2();
        $('#controlBuscadorSecond').select2();

        // Agregar evento click al botón de tipo "submit"
        $('.btn-verde').click(function(event) {
            event.preventDefault(); // Evitar que el botón envíe el formulario normalmente
            enviarFormulario(); // Enviar el formulario con la lista de materiales como JSON
        });
    });