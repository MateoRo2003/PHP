let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

function iniciarApp() {


    mostrarSeccion(); // Muestra y oculta las secciones
    tabs(); // Cambia la sección cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente(); 
    paginaAnterior();
    mostrarTurnos();
    consultarAPI(); // Consulta la API en el backend de PHP
    mostrarHorarios();
    idCliente();
    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita en el objeto
    inicializarCalendario();
    seleccionarHora(); // Añade la hora de la cita en el objeto
    generarHorarios();
    mostrarResumen(); // Muestra el resumen de la cita
}

function mostrarSeccion() {

    // Ocultar la sección que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la sección con el paso...
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs() {

    // Agrega y cambia la variable de paso según el tab seleccionado
    const botones = document.querySelectorAll('.tabs button');
    botones.forEach( boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();

            paso = parseInt( e.target.dataset.paso );
            mostrarSeccion();

            botonesPaginador(); 
        });
    });
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if (paso === 3) {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');

        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }

    mostrarSeccion();
}

function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {

        if(paso <= pasoInicial) return;
        paso--;
        
        botonesPaginador();
    })
}
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {

        if(paso >= pasoFinal) return;
        paso++;
        
        botonesPaginador();
    })
}

async function consultarAPI() {

    try {
        const url = 'http://localhost:3000/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
        
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);

    });
}

function seleccionarServicio(servicio) {
    const { id, duracion } = servicio;
    const { servicios } = cita;

    // Identificar el elemento al que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobar si un servicio ya fue agregado 
    if( servicios.some( agregado => agregado.id === id ) ) {
        // Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id );
        divServicio.classList.remove('seleccionado');
    } else {
        // Agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
    // console.log(cita);
}

function idCliente() {
    cita.id = document.querySelector('#id').value;
}
function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

function inicializarCalendario() {
    var calendarEl = document.getElementById('calendar');
    var fechaInput = document.getElementById('fecha');

    // Obtener la fecha actual
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Resetear las horas de la fecha actual

    // Fecha de ayer
    var yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1); // Restar un día para obtener ayer

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        events: [],
        dateClick: function(info) {
            var selectedDate = new Date(info.dateStr);
            selectedDate.setHours(0, 0, 0, 0); // Asegurarse de comparar solo la fecha, no la hora
            var thirtyDaysFromNow = new Date(today);
            thirtyDaysFromNow.setDate(today.getDate() + 30); // Fecha 30 días adelante

            // Verificar si la fecha seleccionada es anterior a hoy o posterior a los 30 días
            if (selectedDate < yesterday || selectedDate > thirtyDaysFromNow) {
                return; // No permitir selección si la fecha es inválida
            }

            // Eliminar la clase 'selected' de cualquier otra fecha previamente seleccionada
            document.querySelectorAll('.fc-daygrid-day').forEach(function(dayCell) {
                dayCell.classList.remove('selected');
            });

            const dia = selectedDate.getUTCDay();
            if(!([6].includes(dia))){
                    // Añadir la clase 'selected' a la fecha seleccionada
                    info.dayEl.classList.add('selected');

                    // Actualizar el input con la fecha seleccionada
                    fechaInput.value = info.dateStr;

                    // Validar si la fecha seleccionada es un fin de semana
            }
           
            
            if ([6].includes(dia)) {
                
                fechaInput.value = ''; // Limpiar el input si es fin de semana
                return;
                
            } else {
                cita.fecha = info.dateStr; // Actualizar la fecha en la variable 'cita'
            }
        },
        dayCellDidMount: function(info) {
            var currentDay = new Date(info.date);
            currentDay.setHours(0, 0, 0, 0); // Resetear las horas para que solo compare la fecha

            // Comparar con la fecha de ayer y añadir una clase a los días pasados
            if (currentDay <= yesterday) {
                info.el.classList.add('disabled-day'); // Añadir clase para deshabilitar el día
            }

            // Comparar si el día está fuera del rango de +30 días
            var thirtyDaysFromNow = new Date(today);
            thirtyDaysFromNow.setDate(today.getDate() + 30); // Fecha 30 días adelante
            if (currentDay > thirtyDaysFromNow) {
                info.el.classList.add('disabled-day'); // Añadir clase para días fuera del rango
            }

            // Aplicar borde al día actual sin fondo
            if (currentDay.getTime() === today.getTime()) {
                info.el.classList.add('current-day'); // Añadir una clase especial al día actual
            }
        },
        datesSet: function(dateInfo) {
            const displayedMonth = dateInfo.start.getMonth(); // Mes mostrado
            const displayedYear = dateInfo.start.getFullYear(); // Año mostrado
        
            // Calcular el último mes (un mes antes del actual)
            const lastMonth = new Date(today);
            lastMonth.setMonth(today.getMonth() - 1);
        
            // Desactivar el botón de mes anterior si estamos en el mes que se resta (lastMonth)
            const prevButton = document.querySelector('.fc-prev-button');
            prevButton.disabled = (displayedMonth === lastMonth.getMonth() && displayedYear === lastMonth.getFullYear());
        
            // Desactivar el botón de mes siguiente si estamos en el mes actual
            const nextButton = document.querySelector('.fc-next-button');
            nextButton.disabled = (displayedMonth === today.getMonth() && displayedYear === today.getFullYear());
        }
    });

    calendar.render();
}



function mostrarTurnos() {
    const turnoManana = document.querySelector('#turno-manana');
    const turnoTarde = document.querySelector('#turno-tarde');
    
    turnoManana.addEventListener('click', () => mostrarHorarios('manana'));
    turnoTarde.addEventListener('click', () => mostrarHorarios('tarde'));
}

function mostrarHorarios(turno) {
    const contenedorHorarios = document.querySelector('#horarios');
    contenedorHorarios.innerHTML = '';  // Limpiar horarios anteriores
    contenedorHorarios.style.display = 'block';  // Mostrar los horarios

    let horarios = [];
    
    if (turno === 'manana') {
        horarios = generarHorarios('08:00', '11:30', 30);  // Horarios de mañana, intervalos de 30 min
    } else {
        horarios = generarHorarios('14:00', '18:30', 30);  // Horarios de tarde, intervalos de 30 min
    }
       // Solo mostrar el contenedor si hay horarios generados
       
        
    
    horarios.forEach(hora => {
        const botonHora = document.createElement('BUTTON');
        botonHora.classList.add('boton', 'horario');
        botonHora.textContent = hora;
        botonHora.addEventListener('click', () => seleccionarHora(hora));
        
        contenedorHorarios.appendChild(botonHora);
    });

    // Añadir la clase de animación para mostrar los botones
    setTimeout(() => {
        contenedorHorarios.classList.add('fade-in'); // Agregar la clase de animación
    }, 0); // Ejecutar inmediatamente después de que se hayan agregado los botones
}

function generarHorarios(inicio, fin, intervalo) {
    let horarios = [];
    let horaActual = moment(inicio, 'HH:mm');  // Usar moment.js para manipular tiempos
    let horaFinal = moment(fin, 'HH:mm');
    
    while (horaActual <= horaFinal) {
        horarios.push(horaActual.format('HH:mm'));
        horaActual.add(intervalo, 'minutes');
    }
    
    return horarios;
}


function seleccionarHora(hora) {
    // Evitar el reinicio de la página
    event.preventDefault(); // Esto es importante si el botón está dentro de un formulario

    // Limpiar selección previa
    const botonesHorario = document.querySelectorAll('.horario');
    botonesHorario.forEach(boton => {
        boton.classList.remove('seleccionado'); // Elimina la clase de seleccionado de todos los botones
    });

    // Seleccionar el botón actual
    const botonSeleccionado = Array.from(botonesHorario).find(boton => boton.textContent === hora);
    if (botonSeleccionado) {
        botonSeleccionado.classList.add('seleccionado'); // Agrega clase 'seleccionado' al botón que se ha elegido
    }

    // Actualizar la hora en la cita
    cita.hora = hora;
    calcularDuracionServicios(); // Actualizar la duración total
    mostrarResumen(); // Mostrar el resumen con la nueva duración
}


function calcularDuracionServicios() {
    let duracionTotal = 0;
    cita.servicios.forEach(servicio => {
        duracionTotal += servicio.duracion;  // Sumar la duración de cada servicio
    });
    cita.duracionTotal = duracionTotal;
}



async function consultarHorariosOcupados(fecha) {
    const url = `/api/horarios-ocupados?fecha=${fecha}`;
    const resultado = await fetch(url);
    const horariosOcupados = await resultado.json();
    
    // Filtrar los horarios disponibles
    const horariosDisponibles = horarios.filter(hora => !horariosOcupados.includes(hora));
    mostrarHorariosDisponibles(horariosDisponibles);
}





function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {

        const dia = new Date(e.target.value).getUTCDay();

        if( [6, 0].includes(dia) ) {
            e.target.value = '';
            mostrarAlerta('Fines de semana no permitidos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
        
    });
}

// function seleccionarHora() {
//     const inputHora = document.querySelector('#hora');
//     inputHora.addEventListener('input', function(e) {


//         const horaCita = e.target.value;
//         const hora = horaCita.split(":")[0];
//         if(hora < 10 || hora > 18) {
//             e.target.value = '';
//             mostrarAlerta('Hora No Válida', 'error', '.formulario');
//         } else {
//             cita.hora = e.target.value;

//             // console.log(cita);
//         }
//     })
// }










function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // Previene que se generen más de 1 alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    // Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if(desaparece) {
        // Eliminar la alerta
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
  
}


function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpiar el Contenido de Resumen
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0 ) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false);

        return;
    } 

    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;



    // Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    // Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    // Heading para Cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const year = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(year, mes, dia));
    
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);
    
    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

    // Boton para Crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    resumen.appendChild(botonReservar);
}

async function reservarCita() {
    
    const { nombre, fecha, hora, servicios, id } = cita;

    const idServicios = servicios.map( servicio => servicio.id );
    // console.log(idServicios);

    const datos = new FormData();
    
    datos.append('fecha', fecha);
    datos.append('hora', hora );
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);

    // console.log([...datos]);

    try {
        // Petición hacia la api
        const url = '/api/citas'
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        console.log(resultado);
        
        if(resultado.resultado) {
            Swal.fire({
                icon: 'success',
                title: 'Cita Creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al guardar la cita'
        })
    }

    
    // console.log([...datos]);

}