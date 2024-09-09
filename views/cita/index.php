<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<h1 class="nombre-pagina">Crear nuevo Turno</h1>
<p class="descripcion-pagina">Elije tus servicios y coloca tus datos</p>
<div id="app">

    <nav class="tabs">
        <button class="actual"type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Informacion Turno</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>



<div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elije tus servicios a continuación</p>
        <div id="servicios"class="listado-servicios"></div>
 </div>
<div id="paso-2" class="seccion">
        <h2>Tus Datos y Turno</h2>
        <p class="text-center">Coloca tus datos y fecha de tu turno</p>
        <form class="form" novalidate>
        
                    <div class="campo">            
                    <input type="text" id="nombre" value="<?php echo $nombre; ?>"
                    disabled  required/>
                    <label class="lbl-nombre" for="nombre" >
                        <span class="text-nomb">Nombre</span>
                    </label>
                    </div>
                    <div class="campo2">
                    <label class="label" for="label" >Por favor selecione la fecha</label>
                    </div>
                    <div class="campo">                    
                    <input type="date" id="fecha" class="calendar-input" disabled>                                   
                    </div>

                    <div class="campo">
                    <input type="time" id="hora" >
                    <label class="lbl-nombre" for="hora">
                        <span class="text-nomb">Hora</span>
                    </label>
                    </div>           
        </form> 
</div>
    <div id="paso-3" class="seccion">
        <h2>Resumen</h2>
        <p>Verifica que la informacion sea correcta</p>       
    </div>

    <div class="paginacion">

        <button id="anterior"
        class="boton2">&laquo; Anterior</button>

        <button id="siguiente"
        class="boton2"> Siguiente>&raquo;</button>
    
    </div>

</div>
<div id="modalFecha" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Horarios Disponibles</h2>
            <p id="fechaSeleccionada">Aquí aparecerán los horarios disponibles para la fecha seleccionada.</p>
        </div>
    </div>
<?php
    $script="
        <script src='build/js/app.js'></script>
    ";
?>    
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const flatpickrElement = document.querySelector('#fecha'); // Cambia el selector si es necesario
            const modal = document.getElementById('modalFecha');
            const spanClose = document.querySelector('.close');
            const fechaSeleccionada = document.getElementById('fechaSeleccionada');

            // Inicializa Flatpickr
            flatpickr(flatpickrElement, {
                inline: true, // Muestra el calendario en línea
                mode: "single", // Permite seleccionar un solo día
                dateFormat: "d/m/Y", // Formato de la fecha
                locale: "es", // Configura el idioma a español
                minDate: "today", // Establece la fecha mínima como hoy
                maxDate: new Date().fp_incr(30), // Establece la fecha máxima como 30 días desde hoy
                disableMobile: true, // Deshabilita la interfaz móvil de Flatpickr
                defaultDate: "today", // Precarga la fecha de hoy por defecto
                onChange: function(selectedDates) {
                    const fecha = selectedDates[0];
                    if (fecha) {
                        // Muestra el modal
                        modal.style.display = 'block';
                        // Muestra la fecha seleccionada en el modal
                        fechaSeleccionada.textContent = `Horarios disponibles para el ${fecha.toDateString()}`;
                    }
                }
            });

            // Cierra el modal cuando el usuario hace clic en el botón de cerrar
            spanClose.onclick = function() {
                modal.style.display = 'none';
            };

            // Cierra el modal cuando el usuario hace clic fuera del modal
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            };
        });
    </script>
