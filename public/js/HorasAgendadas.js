// Arreglo con los días de la semana
const diasArray = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

// Arreglo con las horas iniciales para bloques completos y medios bloques
const horasInicialesArray = ['07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
const horasInicialesMediasArray = ['07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00'];

// Variables para manejar el estado de las horas deshabilitadas y si hay medias horas
let disabledHoras = [];
let mediaHoras = false;

// Obtener la fecha y el día actual
const date = new Date();
const fullDiaActual = date.toISOString().slice(0, 10);
const diaActual = diasArray[date.getDay()];
$('#diaReservaLab').text(diaActual);

// Determina el endpoint dinámicamente según la página
let endpoint = window.location.pathname.split('/').pop(); // Extrae el nombre del área de la página actual
// Obtiene las horas reservadas del área para el día actual
$.get(`${endpoint}/${fullDiaActual}`, function (data) {
    disabledHoras = data.map(hora => {
        if (terminaEnTreinta(hora.hora_reserva_inicio) || terminaEnTreinta(hora.hora_reserva_final)) {
            mediaHoras = true;
        }
        if (endpoint === 'laboratorio') {
            return {
                inicio: hora.hora_reserva_inicio,
                fin: hora.hora_reserva_final,
                materia: hora.materia,
                actividad: hora.actividad,
                ubicacion: hora.ubicacion
            };
        }
        else{
            return [hora.hora_reserva_inicio, hora.hora_reserva_final];
        }
    });

    console.log('horas reservadas ese día', disabledHoras);

    // Manejar la vista según si hay medias horas o no
    if (mediaHoras) {
        manejarHorasMedias(disabledHoras, horasInicialesMediasArray);
    } else {
        manejarHorasEnteras(disabledHoras, horasInicialesArray);
    }

    inicializarScroll(); // Inicializar el scroll automático
    setInterval(verificarReservacionProxima, 60000); // Verificar cada minuto
    setInterval(() => {
        const horasArray = mediaHoras ? horasInicialesMediasArray : horasInicialesArray;
        refreshHours();
        if (mediaHoras) {
            manejarHorasMedias(disabledHoras, horasArray);
        } else {
            manejarHorasEnteras(disabledHoras, horasArray);
        }
    }, 60000); // Actualizar cada minuto
});


function refreshHours() {
    $.get(`${endpoint}/${fullDiaActual}`, function (data) {
        disabledHoras = data.map(hora => {
            if (terminaEnTreinta(hora.hora_reserva_inicio) || terminaEnTreinta(hora.hora_reserva_final)) {
                mediaHoras = true;
            }
            else{
                mediaHoras = false;
            }
            return {
                inicio: hora.hora_reserva_inicio,
                fin: hora.hora_reserva_final,
                materia: hora.materia,
                actividad: hora.actividad,
                ubicacion: hora.ubicacion
            };
        });
    });
}

// Función para manejar la vista cuando hay medias horas
function manejarHorasMedias(disabledHoras, horasArray) {
    const horasFiltradas = filtrarHorasSegunHoraActual(horasArray); // Filtrar según la hora actual
    const horasDate = horasFiltradas.map(stringToDate); // Convertir a objetos Date
    const horasReservadas = obtenerHorasFinalesSeleccionables(disabledHoras, horasDate); // Obtener horas reservadas
    const horasString = dateToString(horasReservadas).flat(); // Convertir a string

    dibujarHoras(horasFiltradas, horasString, disabledHoras); // Dibujar las horas
}

// Función para manejar la vista cuando no hay medias horas
function manejarHorasEnteras(disabledHoras, horasArray) {
    const horasFiltradas = filtrarHorasSegunHoraActual(horasArray); // Filtrar según la hora actual
    const horasDate = horasFiltradas.map(stringToDate); // Convertir a objetos Date
    const horasReservadas = obtenerHorasFinalesSeleccionables(disabledHoras, horasDate); // Obtener horas reservadas
    const horasString = dateToString(horasReservadas).flat(); // Convertir a string

    dibujarHoras(horasFiltradas, horasString, disabledHoras); // Dibujar las horas
}

// Función para obtener las horas seleccionables finales
function obtenerHorasFinalesSeleccionables(disabledHoras, horasDate) {
    let horasFinalesSeleccionable = [];

    if (endpoint === 'laboratorio') {
        disabledHoras.forEach(rango => {
            const inicioRango = stringToDate(rango.inicio);
            const finRango = stringToDate(rango.fin);
            let rangoHorasReservadas = horasDate.filter(horaD => horaD >= inicioRango && horaD <= finRango);
            rangoHorasReservadas.splice(-1,1); // Eliminar el último elemento del rango
            horasFinalesSeleccionable.push(rangoHorasReservadas);
        });
    }
    else{
        disabledHoras.forEach(rango => {
            const inicioRango = stringToDate(rango[0]);
            const finRango = stringToDate(rango[1]);
            let rangoHorasReservadas = horasDate.filter(horaD => horaD >= inicioRango && horaD <= finRango);
            rangoHorasReservadas.splice(-1,1); // Eliminar el último elemento del rango
            horasFinalesSeleccionable.push(rangoHorasReservadas);
        });
    }
    
    return horasFinalesSeleccionable;
}

// Función para dibujar las horas en el contenedor
function dibujarHoras(horasArray, horasDisabledString, disabledHoras) {
    $('#horasContainer').empty(); // Limpiar el contenedor antes de dibujar las horas
    $('#horasContainerAtras').empty(); // Limpiar el contenedor antes de dibujar las horas

    //Ordena los arreglos
    horasArray = ordenarArregloHoras(horasArray);
    horasDisabledString = ordenarArregloHoras(horasDisabledString);
    
    if (endpoint === 'laboratorio') {
        disabledHoras = ordenarPorHoraInicio(disabledHoras);
        //Filtrado de horas según la ubicación
        let reservacionesAdelante = disabledHoras.filter(reserva => reserva.ubicacion === 'Adelante'); 
        let reservacionesAtras = disabledHoras.filter(reserva => reserva.ubicacion === 'Atrás');

        //Completa los huecos libres que hay en el horario
        reservacionesAdelante = completarHorario(reservacionesAdelante);
        reservacionesAtras = completarHorario(reservacionesAtras);

        //Renderiza los bloques de adelante
        reservacionesAdelante.forEach((horaIterable, index) => {
            const claseHora = horaIterable.materia === 'Libre' ? 'bg-success' : 'bg-danger'; // Verificar si la hora está reservada
            $('#horasContainer').append(
                `<div class='${claseHora} p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-column' id='horaLab-${index}'>
                    <div>${horaIterable.inicio} - ${horaIterable.fin} hrs</div>
                    <div class=''>
                    <div>${horaIterable.actividad}</div>
                    <div>${horaIterable.materia}
                    <div/>
                </div>`
            );
        });

        //Renderiza los bloques de atrás
        reservacionesAtras.forEach((horaIterable, index) => {
            const claseHora = horaIterable.materia === 'Libre' ? 'bg-success' : 'bg-danger'; // Verificar si la hora está reservada
            $('#horasContainerAtras').append(
                `<div class='${claseHora} p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-column' id='horaLab-${index}'>
                    <div>${horaIterable.inicio} - ${horaIterable.fin} hrs</div>
                    <div class=''>
                    <div>${horaIterable.actividad}</div>
                    <div>${horaIterable.materia}
                    <div/>
                </div>`
            );
        });
    }
    else{
        horasArray.forEach((hora, index) => {
            const reservada = horasDisabledString.includes(hora); // Verificar si la hora está reservada
            const claseHora = reservada ? 'bg-danger' : 'bg-success';
            const horaFinal = index === horasArray.length - 1 ? '22:00' : horasArray[index + 1];

            // Encontrar la reservación que hay en esa hora
            let reserva;
            reserva = disabledHoras.find(r => {
                const inicioRango = stringToDate(r[0]);
                const finRango = stringToDate(r[1]);
                const horaDate = stringToDate(hora);
                return horaDate >= inicioRango && horaDate < finRango;
            });

            $('#horasContainer').append(
                `<div class='${claseHora} p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-column' id='horaLab-${index}'>
                    <div>${hora} - ${horaFinal} hrs</div>
                    <div class=''>
                    <div/>
                </div>`
            );
        });
    }
}

// Función para redondear la hora actual a la hora más cercana con :00 minutos
function redondearHoraActual() {
    const ahora = new Date();
    ahora.setMinutes(0, 0, 0); // Establecer los minutos, segundos y milisegundos a 0
    return ahora;
}

// Función para filtrar las horas según la hora actual
function filtrarHorasSegunHoraActual(horasArray) {
    const horaActual = redondearHoraActual();
    return horasArray.filter(hora => stringToDate(hora) >= horaActual);
}

// Función para inicializar el scroll automático
function inicializarScroll() {
    const alturaVentana = window.innerHeight;
    const alturaScroll = alturaVentana * 0.5; // 50% de la altura de la ventana
    $('#horasContainer').css({
        'overflow-y': 'hidden',
        'height': `${alturaScroll}px`
    });

    $('#horasContainerAtras').css({
        'overflow-y': 'hidden',
        'height': `${alturaScroll}px`
    });

    let intervalo = null;
    let intervalo2 = null;
    let scrollsCompletados = 0; // Contador para coordinar el reinicio de ambos scrolls

    function iniciarScroll() {
        intervalo = setInterval(scrollDown, 50);
    }

    function iniciarScrollAtras() {
        intervalo2 = setInterval(scrollDownAtras, 50);
    }

    function scrollDown() {
        const $contenedor = $('#horasContainer');
        const alturaContenedor = $contenedor.outerHeight();
        const alturaScroll = $contenedor.prop('scrollHeight');
        const scrollTop = $contenedor.scrollTop();

        if (scrollTop >= alturaScroll - alturaContenedor) {
            clearInterval(intervalo);
            scrollsCompletados++;
            checkScrollsCompletados();
        } else {
            $contenedor.scrollTop(scrollTop + 3);
        }
    }

    function scrollDownAtras() {
        const $contenedor = $('#horasContainerAtras');
        const alturaContenedor = $contenedor.outerHeight();
        const alturaScroll = $contenedor.prop('scrollHeight');
        const scrollTop = $contenedor.scrollTop();

        if (scrollTop >= alturaScroll - alturaContenedor) {
            clearInterval(intervalo2);
            scrollsCompletados++;
            checkScrollsCompletados();
        } else {
            $contenedor.scrollTop(scrollTop + 3);
        }
    }

    function checkScrollsCompletados() {
        if (scrollsCompletados >= 2) {
            // Reiniciar ambos scrolls al mismo tiempo
            setTimeout(() => {
                $('#horasContainer').scrollTop(0);
                $('#horasContainerAtras').scrollTop(0);
                setTimeout(() => {
                    scrollsCompletados = 0; // Reiniciar el contador
                    iniciarScroll();
                    iniciarScrollAtras();
                }, 1500); // Espera de 1 segundo y medio antes de reiniciar el scroll nuevamente
            }, 1500); // Espera de 1 segundo y medio al final antes de reiniciar el scroll
        }
    }

    // Espera inicial de 1 segundo y medio antes de comenzar el scroll
    setTimeout(iniciarScroll, 1500);
    setTimeout(iniciarScrollAtras, 1500);
}

// Función para verificar si hay una reservación próxima y mostrar un modal de alerta
function verificarReservacionProxima() {
    const horaAlerta = new Date(Date.now() + 10 * 60000);
    disabledHoras.forEach(rango => {
        let inicioRango;
        if (endpoint === 'laboratorio') {
            inicioRango = stringToDate(rango.inicio);   
        }
        else
        {
            inicioRango = stringToDate(rango[0]);
        }
        if (horaAlerta.getHours() === inicioRango.getHours() && horaAlerta.getMinutes() === inicioRango.getMinutes()) {
            mostrarModal();
        }
    });
}

// Función para convertir una cadena de hora a un objeto Date
function stringToDate(hourString) {
    const [hours, minutes] = hourString.split(':').map(Number);
    return new Date(date.getFullYear(), date.getMonth(), date.getDate(), hours, minutes);
}

// Función para convertir un arreglo de objetos Date a cadenas de hora
function dateToString(hourDate) {
    return hourDate.map(arr => arr.map(date => date.toTimeString().slice(0, 5)));
}

// Función para ordenar las horas en un arreglo bidimensional
function ordenaHorasBidimensional(a, b) {
    return a[0].localeCompare(b[0]) || a[1].localeCompare(b[1]);
}

// Función para verificar si una cadena de hora termina en ":30"
function terminaEnTreinta(cadenaHora) {
    return cadenaHora.endsWith("30");
}

// Función para mostrar un modal de alerta
function mostrarModal() {
    $('#alertaModalReservacion').modal('show');
    setTimeout(() => $('#alertaModalReservacion').modal('hide'), 10000);
}

function ordenarArregloHoras(horas) {
    return horas.sort((a, b) => {
        let [horaA, minutoA] = a[0].split(':').map(Number);
        let [horaB, minutoB] = b[0].split(':').map(Number);
        
        if (horaA !== horaB) {
            return horaA - horaB; // Comparar horas
        } else {
            return minutoA - minutoB; // Comparar minutos si las horas son iguales
        }
    });
}

function ordenarPorHoraInicio(arr) {
    return arr.sort((a, b) => {
        const [hoursA, minutesA] = a.inicio.split(':').map(Number);
        const [hoursB, minutesB] = b.inicio.split(':').map(Number);

        const dateA = new Date(0, 0, 0, hoursA, minutesA); // Crear objetos Date para comparar
        const dateB = new Date(0, 0, 0, hoursB, minutesB);

        return dateA - dateB;
    });
}

function completarHorario(reservaciones) {
    // Primero, ordenamos las reservaciones por la hora de inicio
    reservaciones.sort((a, b) => {
        const [hoursA, minutesA] = a.inicio.split(':').map(Number);
        const [hoursB, minutesB] = b.inicio.split(':').map(Number);
        return new Date(0, 0, 0, hoursA, minutesA) - new Date(0, 0, 0, hoursB, minutesB);
    });

    // Definimos el horario de apertura y cierre
    const horaApertura = '07:00';
    const horaCierre = '21:00';

    // Arreglo para almacenar el resultado
    const resultado = [];

    // Inicializamos la hora de referencia con la hora de apertura
    let horaReferencia = horaApertura;

    reservaciones.forEach(reserva => {
        if (horaReferencia < reserva.inicio) {
            // Si hay un hueco entre la hora de referencia y la hora de inicio de la reserva actual
            resultado.push({
                inicio: horaReferencia,
                fin: reserva.inicio,
                materia: 'Libre',
                actividad: '',
                ubicacion: ''
            });
        }
        // Añadimos la reserva actual al resultado
        resultado.push(reserva);
        // Actualizamos la hora de referencia con la hora de fin de la reserva actual
        horaReferencia = reserva.fin;
    });

    // Si queda un hueco entre la última reserva y la hora de cierre
    if (horaReferencia < horaCierre) {
        resultado.push({
            inicio: horaReferencia,
            fin: horaCierre,
            materia: 'Libre',
            actividad: '',
            ubicacion: ''
        });
    }

    return resultado;
  }