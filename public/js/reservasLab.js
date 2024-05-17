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

// Clase para manejar las horas de reserva
class HoraClass {
    constructor(horas, actividad, localizacion, ocupado) {
        this.horas = horas;
        this.actividad = actividad;
        this.localizacion = localizacion;
        this.ocupado = ocupado;
    }

    getRangoHoras(arrayHoras) {
        return obtenerRangoHoras(this.horas[0], this.horas[1], arrayHoras);
    }
}

// Obtener las horas reservadas del servidor para el día actual
$.get(`laboratorio/${fullDiaActual}`, function (data) {
    disabledHoras = data.map(hora => {
        if (terminaEnTreinta(hora.hora_reserva_inicio) || terminaEnTreinta(hora.hora_reserva_final)) {
            mediaHoras = true;
        }
        return [hora.hora_reserva_inicio, hora.hora_reserva_final];
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
        if (mediaHoras) {
            manejarHorasMedias(disabledHoras, horasArray);
        } else {
            manejarHorasEnteras(disabledHoras, horasArray);
        }
    }, 60000); // Actualizar cada minuto
});

// Función para manejar la vista cuando hay medias horas
function manejarHorasMedias(disabledHoras, horasArray) {
    const horasFiltradas = filtrarHorasSegunHoraActual(horasArray); // Filtrar según la hora actual
    const horasDate = horasFiltradas.map(stringToDate); // Convertir a objetos Date
    const horasReservadas = obtenerHorasFinalesSeleccionables(disabledHoras, horasDate); // Obtener horas reservadas
    const horasString = dateToString(horasReservadas).flat(); // Convertir a string

    dibujarHoras(horasFiltradas, horasString); // Dibujar las horas
}

// Función para manejar la vista cuando no hay medias horas
function manejarHorasEnteras(disabledHoras, horasArray) {
    const horasFiltradas = filtrarHorasSegunHoraActual(horasArray); // Filtrar según la hora actual
    const horasDate = horasFiltradas.map(stringToDate); // Convertir a objetos Date
    const horasReservadas = obtenerHorasFinalesSeleccionables(disabledHoras, horasDate); // Obtener horas reservadas
    const horasString = dateToString(horasReservadas).flat(); // Convertir a string

    dibujarHoras(horasFiltradas, horasString); // Dibujar las horas
}

// Función para obtener las horas seleccionables finales
function obtenerHorasFinalesSeleccionables(disabledHoras, horasDate) {
    let horasFinalesSeleccionable = [];
    disabledHoras.forEach(rango => {
        const inicioRango = stringToDate(rango[0]);
        const finRango = stringToDate(rango[1]);
        let rangoHorasReservadas = horasDate.filter(horaD => horaD >= inicioRango && horaD <= finRango);
        rangoHorasReservadas.splice(-1,1); // Eliminar el último elemento del rango
        horasFinalesSeleccionable.push(rangoHorasReservadas);
    });
    return horasFinalesSeleccionable;
}

// Función para dibujar las horas en el contenedor
function dibujarHoras(horasArray, horasString) {
    $('#horasContainer').empty(); // Limpiar el contenedor antes de dibujar las horas
    horasArray.forEach((hora, index) => {
        const reservada = horasString.includes(hora); // Verificar si la hora está reservada
        const claseHora = reservada ? 'bg-danger' : 'bg-success';
        const horaFinal = index === horasArray.length - 1 ? '22:00' : horasArray[index + 1];
        $('#horasContainer').append(
            `<div class='${claseHora} p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-row justify-content-between' id='horaLab-${index}'>
                <div>${hora} - ${horaFinal} hrs</div>
            </div>`
        );
    });
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
    $('#horasContainer').css({ 'overflow-y': 'hidden', 'height': '400px' });
    let intervalo = setInterval(scrollDown, 50);

    function scrollDown() {
        const $contenedor = $('#horasContainer');
        const alturaContenedor = $contenedor.outerHeight();
        const alturaScroll = $contenedor.prop('scrollHeight');
        const scrollTop = $contenedor.scrollTop();

        if (scrollTop >= alturaScroll - alturaContenedor - 60) {
            clearInterval(intervalo);
            setTimeout(() => intervalo = setInterval(scrollDown, 50), 1000);
            $contenedor.scrollTop(0);
        } else {
            $contenedor.scrollTop(scrollTop + 3);
        }
    }
}

// Función para verificar si hay una reservación próxima y mostrar un modal de alerta
function verificarReservacionProxima() {
    const horaAlerta = new Date(Date.now() + 10 * 60000);
    disabledHoras.forEach(rango => {
        const inicioRango = stringToDate(rango[0]);
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
