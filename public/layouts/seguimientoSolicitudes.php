<input type="text" id="busqueda" placeholder="Buscar y seleccionar...">
                  <select id="opciones" required style="width: 75%;">
                     <option value="" selected disabled>Seleccione una opción</option>
                  </select>

                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script>
                     $(document).ready(function() {
                        // Cargar opciones iniciales
                        cargarOpciones('');

                        // Evento de cambio en el campo de búsqueda
                        $('#busqueda').on('input', function() {
                           var searchTerm = $(this).val().trim();
                           cargarOpciones(searchTerm);
                        });

                        // Función para cargar opciones mediante AJAX
                        function cargarOpciones(searchTerm) {
                           $.ajax({
                              url: '/Banco/public/layouts/buscar_opciones.php', // Archivo PHP que maneja la búsqueda y retorna resultados
                              method: 'POST',
                              data: {
                                 searchTerm: searchTerm
                              },
                              dataType: 'json',
                              success: function(data) {
                                 // Limpiar opciones existentes
                                 $('#opciones').empty();
                                 $('#opciones').append('<option value="" selected disabled>Seleccione una opción</option>');

                                 // Agregar nuevas opciones
                                 $.each(data, function(index, option) {
                                    $('#opciones').append('<option value="' + option.clave + ' - ' + option.descripcion + '">' + option.clave + ' - ' + option.descripcion + '</option>');
                                 });
                              },
                              error: function(xhr, status, error) {
                                 console.log('Error en la solicitud AJAX: ' + error);
                              }
                           });
                        }
                     });
                  </script>
               </div>
            </div>
            <!-- <script src="/Banco/public/js/realizarPedidoForm.js"></script> -->