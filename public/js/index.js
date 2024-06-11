const horasAgendablesArray = [
    "07:00", "07:30", "08:00", "08:30", "09:00", "09:30", "10:00", "10:30", 
    "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", 
    "15:00", "15:30", "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", 
    "19:00", "19:30", "20:00", "20:30", "21:00"
];

let horasInicialesDate = [];
let disabledDays = [];

$(function () {
    obtenerAñoFooter();
    cerrarModal();
    
    //Crea un arreglo de las horas agendables a tipo Date para poder iterar y hacer comparaciones de tiempo
    horasAgendablesArray.forEach(hora => horasInicialesDate.push(stringToDate(hora)));

    //Se obtienen los días deshabilitados
    $.get("obtener-dias-deshabilitados", function (data) {
        disabledDays = data.map(dias => dias.dias_deshabilitado);
        //Se configuran los calendarios de las áreas
        ["#fecha_reservada", "#fecha_reservada_auditorio", "#fecha_reservada_magna", "#fecha_reservada_sala_video"]
            .forEach(selector => initializeFlatpickr(selector));
    });

    //Se configuran los inputs de las horas de las áreas
    ["#hora_reserva_inicio", "#hora_reserva_inicio_auditorio", "#hora_reserva_inicio_magna", "#hora_reserva_inicio_sala_video"]
        .forEach(selector => disableHourInputs(selector));

    //Cuando se selecciona un dia del calendario
    handleDayChange("#fecha_reservada", "#hora_reserva_inicio", "#hora_reserva_final", "#pantallaCarga", "/horas-reservadas/laboratorio/");
    handleDayChange("#fecha_reservada_auditorio", "#hora_reserva_inicio_auditorio", "#hora_reserva_final_auditorio", "#pantallaCargaAuditorio", "/horas-reservadas/auditorio/");
    handleDayChange("#fecha_reservada_magna", "#hora_reserva_inicio_magna", "#hora_reserva_final_magna", "#pantallaCargaMagna", "/horas-reservadas/aula-magna/");
    handleDayChange("#fecha_reservada_sala_video", "#hora_reserva_inicio_sala_video", "#hora_reserva_final_sala_video", "#pantallaCargaSalaVideo", "/horas-reservadas/sala-videoconferencias/");

    //Determina la ubicacion en las reservaciones del auditorio
    $('#usa_proyector').on('click', function () {
        $("#ubicacion").val($(this).is(":checked") ? "Adelante" : "Atrás");
    });

    //Muestra el formulario correspondiente dependiendo de lo que se eligio y oculta los otros formularios
    $("#radioContainer").on("click", function (event) {
        hideAllForms();
        let target = event.target;
        let targetId = target.type === "radio" ? target.id : target.htmlFor;
        if (targetId) {
            $(`#form${targetId.replace("radio", "")}`).removeClass("hidden");
        }
    });

    function hideAllForms() {
        $(".formulario").addClass("hidden");
    }
});

// Deshabilitar inputs de horas
function disableHourInputs(selectorPrefix) {
    switch (selectorPrefix) {
        case '#hora_reserva_inicio':
            $(`${selectorPrefix}`).prop("disabled", true);
            $(`#hora_reserva_final`).prop("disabled", true);
            break;
        case "#hora_reserva_inicio_auditorio":
            $(`${selectorPrefix}`).prop("disabled", true);
            $(`#hora_reserva_final_auditorio`).prop("disabled", true);
            break;
        case "#hora_reserva_inicio_magna":
            $(`${selectorPrefix}`).prop("disabled", true);
            $(`#hora_reserva_final_magna`).prop("disabled", true);
            break;
        case "#hora_reserva_inicio_sala_video":
            $(`${selectorPrefix}`).prop("disabled", true);
            $(`#hora_reserva_final_sala_video`).prop("disabled", true);
            break;
        default:
            break;
    }
}

// Inicializa Flatpickr con opciones comunes
function initializeFlatpickr(selector) {
    flatpickr(selector, {
        minDate: new Date().fp_incr(1), // A partir del día de mañana
        maxDate: new Date().fp_incr(15), // 15 días a partir de hoy
        disable: disabledDays,
    });
}

// Configura timepicker para el input de hora final
function configureTimepicker(selector, finalDisablesHours, durationTime) {
    $(selector).timepicker({
        minTime: "7:00am",
        maxTime: "9:01pm",
        step: 30,
        timeFormat: "H:i",
        showDuration: true,
        durationTime: durationTime,
        scrollDefault: durationTime,
        disableTimeRanges: finalDisablesHours,
    });
}

// Maneja el cambio de día de reserva para cualquier sala
function handleDayChange(diaReservaSelector, horaInicioSelector, horaFinalSelector, cargaSelector, url) {
    $(diaReservaSelector).on("change", function () {
        let fechaSeleccionada = $(this).val();
        $(this).prop("disabled", true);
        disableHourInputs(horaInicioSelector);

        $(horaInicioSelector).val("");
        $(horaFinalSelector).val("");
        $(cargaSelector).removeClass("hidden");

        $.get(url + fechaSeleccionada, function (data) {
            let disabledHoras = [];
            let disabledHorasString = [];
            let horasFinalesSeleccionable = [];
            let rangoHorasReservadas = [];

            data.forEach((hora) => {
                disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
            });

            disabledHoras.forEach((rango) => {
                disabledHorasString.push([rango[0], rango[1]]);
                const inicioRango = stringToDate(rango[0]);
                const finRango = stringToDate(rango[1]);

                horasInicialesDate.forEach((horaD) => {
                    if (horaD >= inicioRango && horaD <= finRango) {
                        rangoHorasReservadas.push(horaD);
                    }
                });

                rangoHorasReservadas.shift();
                horasFinalesSeleccionable.push(rangoHorasReservadas);
                rangoHorasReservadas = [];
            });

            datesToString(horasFinalesSeleccionable);

            $(horaInicioSelector).timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });

            $(cargaSelector).addClass("hidden");
            $(diaReservaSelector).prop("disabled", false);
            $(horaInicioSelector).prop("disabled", false);

            $(horaInicioSelector).on("change", function () {
                $(horaFinalSelector).val("");
                let horaSelDate = stringToDate($(horaInicioSelector).val());
                let postDisabledHours = "";
                let tempDisHours = "";
                let pastHours = disablePastHoursString($(horaInicioSelector).val());
                pastHours.push(horaSelDate);
                let pastHours2 = datesObjectsToString(pastHours);
                let indiceHora = obtenerIndiceHora($(horaInicioSelector).val(), horasAgendablesArray);
                pastHours2.push(siguienteHora(indiceHora));
                let horasDisables2 = firstAndLast(pastHours2);
                let finalDisablesHours = obtenRangosDeshabilitados(disabledHorasString);

                //Deshabilita las horas posteriores a una cita ya reservada
                disabledHorasString.forEach((rango) => {
                    if (stringToDate(rango[0]) > horaSelDate) {
                        tempDisHours = datesObjectsToString(
                            disablePostHoursString(rango[0])
                        );
                        tempDisHours.push("22:00");
                        postDisabledHours = firstAndLast(tempDisHours);
                    }
                });

                finalDisablesHours.push(postDisabledHours);
                finalDisablesHours.push(horasDisables2);

                configureTimepicker(horaFinalSelector, finalDisablesHours, $(horaInicioSelector).val());

                if ($(horaInicioSelector).val()) {
                    $(horaFinalSelector).prop("disabled", false);
                }
            });
        });
    });
}

// Funciones auxiliares
function stringToDate(hourString) {
    const [hours, minutes] = hourString.split(":").map(Number);
    return new Date(new Date().setHours(hours, minutes, 0, 0));
}

function datesObjectsToString(dates) {
    return dates.map(singleDateToString);
}

function datesToString(hourDate) {
    hourDate.forEach(innerArray => {
        let tempArray = innerArray.map(element => {
            let horaStringTemp = element.getHours() + ":00";
            return horaStringTemp;
        });
        // disabledHorasFinales.push(tempArray);
    });
}

function singleDateToString(hourDate) {
    let horaStringTemp = hourDate.getHours();
    horaStringTemp += hourDate.getMinutes() == 30 ? ":30" : ":00";
    return horaStringTemp;
}

function disablePastHoursString(hourInicial) {
    let tempArray = [];
    let horaI = stringToDate(hourInicial);
    horasInicialesDate.forEach(hora => {
        if (hora < horaI) {
            tempArray.push(hora);
        }
    });
    return tempArray;
}

//Deshabilita las horas posteriores de una hora
function disablePostHoursString($horaInicial) {
    let tempArray = [];
    let horaI = stringToDate($horaInicial);
    for (let i = 0; i < horasInicialesDate.length; i++) {
        if (horasInicialesDate[i] > horaI) {
            tempArray.push(horasInicialesDate[i]);
        }
    }
    return tempArray;
}

function firstAndLast(array) {
    return [array[0], array[array.length - 1]];
}

function obtenerIndiceHora(hora, horasAgendablesArray) {
    return horasAgendablesArray.indexOf(hora);
}

function siguienteHora(indiceHora) {
    return horasAgendablesArray[indiceHora + 1] || horasAgendablesArray[0];
}

function obtenRangosDeshabilitados(disabledHorasString) {
    return disabledHorasString.map(rango => {
        let rangos = obtenerRangoHoras(rango[0], rango[1], horasAgendablesArray);
        if (rangos.length === 2) {
            return [rangos[1], siguienteHora(horasAgendablesArray.indexOf(rangos[1]))];
        } else if (rangos.length > 2) {
            rangos.shift();
            return firstAndLast(rangos);
        }
        return [];
    });
}

function obtenerRangoHoras(horaInicial, horaFinal, arregloHoras) {
    let indiceInicial = arregloHoras.indexOf(horaInicial);
    let indiceFinal = arregloHoras.indexOf(horaFinal);
    if (indiceInicial === -1 || indiceFinal === -1) return [];
    if (indiceInicial > indiceFinal) [indiceInicial, indiceFinal] = [indiceFinal, indiceInicial];
    return arregloHoras.slice(indiceInicial, indiceFinal + 1);
}

function cerrarModal() {
    $('#close-btn').on('click', function () {
        $(this).parent().remove();
    });
}

function obtenerAñoFooter() {
    $("#lbAño").text(new Date().getFullYear());
}
