const checkLabComp = $("#checkboxLabComp");
const diaReservaLabo = $("#fecha_reservada");
const diaReservaAuditorio = $("#fecha_reservada_auditorio");
const diaReservaSalaVideo = $("#fecha_reservada_sala_video");
const diaReservaMagna = $("#fecha_reservada_magna");
const hora_reserva_inicio = $("#hora_reserva_inicio");
const hora_reserva_final = $("#hora_reserva_final");

//En horasAgendablesArray van todas las horas en las que el usuario podrá reservar su cita
let horasAgendablesArray = [
    "07:00",
    "07:30",
    "08:00",
    "08:30",
    "09:00",
    "09:30",
    "10:00",
    "10:30",
    "11:00",
    "11:30",
    "12:00",
    "12:30",
    "13:00",
    "13:30",
    "14:00",
    "14:30",
    "15:00",
    "15:30",
    "16:00",
    "16:30",
    "17:00",
    "17:30",
    "18:00",
    "18:30",
    "19:00",
    "19:30",
    "20:00",
    "20:30",
    "21:00",
];

let horasInicialesDate = [];
let horasFinalesSeleccionable = [];
let rangoHorasReservadas = [];

let disabledHoras = []; //horas deshabilitadas para la hora de reserva inicial
let disabledHorasString = [];
let disabledHorasFinales = []; //horas deshabilitadas para la hora de reserva final
let pastHours = []; //Horas pasadas de la hora inicial de reserva que selecciono el usuario

let disabledDays = [];

$(function() {
    $("#formularioLabComp").addClass("hidden");

    checkLabComp.on("click", function () {
        console.dir($("#radioContainer"));
        $("#formularioLabComp").removeClass("hidden");
    });

    $("#radioContainer").on("click", function (e) {
        var target = e.target;
        if (target.type === "radio" && target.name === "chAreas") {
            mostrarFormulario(target.id.replace("checkbox", "formulario"));
        }
    });

    //Boton para cerrar la alerta que aparece cuando se guarda una reservación
    $('#close-btn').on('click', function (e) {
    $(this).parent().remove();
    })

    //Se obtiene el dia de hoy
    const date = new Date();
    let fullDiaActual = date.toJSON().slice(0, 10);
    console.log(fullDiaActual); // "yyyy-mm-dd"

    //Convierte el arreglo de horas (string) validas para agendar a horas tipo Date para hacer comparaciones de tiempo
    horasAgendablesArray.forEach((hora) => {
        horasInicialesDate.push(stringToDate(hora));
    });

    //
    $.get("all-dias-inhabilitados-laboratorio", function (data) {
        data.forEach((dias) => {
            disabledDays.push(dias.diaDeshabiliado);
        });

        console.log(disabledDays);

        flatpickr("#fecha_reservada", {
            minDate: new Date().fp_incr(1), // A partir del día de mañana
            maxDate: new Date().fp_incr(15), // 15 días a partir de hoy
            disable: disabledDays,
        });
    
        flatpickr("#fecha_reservada_auditorio", {
            minDate: new Date().fp_incr(1), // A partir del día de mañana
            maxDate: new Date().fp_incr(15), // 15 días a partir de hoy
            disable: disabledDays,
        });

        flatpickr("#fecha_reservada_magna", {
            minDate: new Date().fp_incr(1), // A partir del día de mañana
            maxDate: new Date().fp_incr(15), // 15 días a partir de hoy
            disable: disabledDays,
        });

        flatpickr("#fecha_reservada_sala_video", {
            minDate: new Date().fp_incr(1), // A partir del día de mañana
            maxDate: new Date().fp_incr(15), // 15 días a partir de hoy
            disable: disabledDays,
        });
    });

   

    //Inicialmente se deshabilitan los inputs de seleccionar hora hasta que el usuario escoja un día para hacer la reservación
    $("#hora_reserva_inicio").prop("disabled", true);
    $("#hora_reserva_final").prop("disabled", true);

     //Inicialmente se deshabilitan los inputs de seleccionar hora hasta que el usuario escoja un día para hacer la reservación
     $("#hora_reserva_inicio_auditorio").prop("disabled", true);
     $("#hora_reserva_final_auditorio").prop("disabled", true);

     //Inicialmente se deshabilitan los inputs de seleccionar hora hasta que el usuario escoja un día para hacer la reservación
     $("#hora_reserva_inicio_magna").prop("disabled", true);
     $("#hora_reserva_final_magna").prop("disabled", true);

     //Inicialmente se deshabilitan los inputs de seleccionar hora hasta que el usuario escoja un día para hacer la reservación
     $("#hora_reserva_inicio_sala_video").prop("disabled", true);
     $("#hora_reserva_final_sala_video").prop("disabled", true);



    // Cuando el usuario elija el laboratorio para reservar
    diaReservaLabo.on("change", function () {
        let fechaSeleccionada = $(this).val(); //Fecha seleccionada por el usuario en el calendario

        // Se deshabilita el input de la fecha y horas para que el usuario no haga otra solicitud
        // hasta que se termine la primera
        $(this).prop("disabled", true);
        $("#hora_reserva_inicio").prop("disabled", true);
        $("#hora_reserva_final").prop("disabled", true);

        // Se limpian los inputs de las horas
        $("#hora_reserva_inicio").val("");
        $("#hora_reserva_final").val("");

        //Muestra mensaje de que se estan cargando las horas reservadas
        $("#pantallaCarga").removeClass("hidden");

        //Se hace la solicitud ajax para obtener las horas reservadas
        $.get("/horas-reservadas/laboratorio/" + fechaSeleccionada, function (data) {
            console.log("Horas agendadas:", data);

            disabledHoras = [];
            disabledHorasString = [];

            //Obtiene las horas deshabilitadas para el input 1 (hora reserva inicial)
            data.forEach((hora) => {
                // Se agrega cada rango de hora reservada al arreglo
                disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
            });

            console.log("horas reservadas ese día", disabledHoras);

            // Obtiene las horas deshabilitadas para la hora final de reserva
            disabledHoras.forEach((rango) => {
                disabledHorasString.push([rango[0], rango[1]]);

                const inicioRango = stringToDate(rango[0]);
                const finRango = stringToDate(rango[1]);

                horasInicialesDate.forEach((horaD) => {
                    if (horaD >= inicioRango && horaD <= finRango) {
                        reservada = true;
                        rangoHorasReservadas.push(horaD);
                    }
                });

                rangoHorasReservadas.shift();
                horasFinalesSeleccionable.push(rangoHorasReservadas);
                rangoHorasReservadas = [];
            });

            //Convierte las horas finales para reservar de tipo Date a string
            datesToString(horasFinalesSeleccionable);

            //Se deshabilitan las horas ya agendadas para ese día en el input de hora inicial
            $("#hora_reserva_inicio").timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });

            //Se quita el mensaje de carga
            $("#pantallaCarga").addClass("hidden");

            //Se vuelve a habilitar el input de la fecha
            diaReservaLabo.prop("disabled", false);

            //Se habilita la hora de reserva inicial
            $("#hora_reserva_inicio").prop("disabled", false);

            //Cuando el usuario selecciona una hora de reserva inicial
            //Se activa el input de hora_reserva_final
            $("#hora_reserva_inicio").on("change", function () {
                //Limpia la hora reserva final
                $("#hora_reserva_final").val("");

                let horaSelDate = stringToDate($("#hora_reserva_inicio").val());

                //Limpieza de variables para cuando se seleccionen otras fechas
                let postDisabledHours = "";
                let tempDisHours = "";
                let pastHours = "";
                let pastHours2 = "";
                let indiceHora = "";
                let horasDisables2 = "";
                let finalDisablesHours = "";

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

                //Deshabilita las horas ya pasadas de la hora de inicio seleccionada
                pastHours = disablePastHoursString($("#hora_reserva_inicio").val()); //Regresa un array de string de las horas pasadas a la seleccionada
                pastHours.push(horaSelDate); // Agrega la hora seleccionada como hora pasada
                pastHours2 = datesObjectsToString(pastHours); //Convierte ese array de strings a un array de tipo Date para hacer comparaciones de tiempo
                indiceHora = obtenerIndiceHora(
                    $("#hora_reserva_inicio").val(),
                    horasAgendablesArray
                ); //Obtiene el indice de la siguiente hora
                pastHours2.push(siguienteHora(indiceHora)); //Agrega la siguiente hora de la hora seleccionada para deshabilitarla
                horasDisables2 = firstAndLast(pastHours2); //Regresa el rango con las horas deshabilitadas pasadas
                finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene las horas pasadas en un arreglo de cadenas

                finalDisablesHours.push(postDisabledHours); //Se unen las horas deshabilitadas despues de una agenda
                finalDisablesHours.push(horasDisables2); //Se unen las horas agendadas con las horas deshabiliadas

                //Se configura el input y las horas en las que el usuario puede terminar su reservación
                $("#hora_reserva_final").timepicker({
                    minTime: "7:00am",
                    maxTime: "9:01pm",
                    step: 30,
                    timeFormat: "H:i",
                    showDuration: true,
                    durationTime: $("#hora_reserva_inicio").val(),
                    scrollDefault: $("#hora_reserva_inicio").val(),
                    disableTimeRanges: finalDisablesHours,
                });

                //Se meustra el input de horas 2
                if ($("#hora_reserva_inicio").val()) {
                    $("#hora_reserva_final").prop("disabled", false);
                }
            });
        });
    });


    // Cuando el usuario elija el auditorio para reservar
    diaReservaAuditorio.on("change", function () {
        let fechaSeleccionada = $(this).val(); //Fecha seleccionada por el usuario en el calendario

        // Se deshabilita el input de la fecha y horas para que el usuario no haga otra solicitud
        // hasta que se termine la primera
        $(this).prop("disabled", true);
        $("#hora_reserva_inicio_auditorio").prop("disabled", true);
        $("#hora_reserva_final_auditorio").prop("disabled", true);

        // Se limpian los inputs de las horas
        $("#hora_reserva_inicio_auditorio").val("");
        $("#hora_reserva_final_auditorio").val("");

        //Muestra mensaje de que se estan cargando las horas reservadas
        $("#pantallaCargaAuditorio").removeClass("hidden");

        //Se hace la solicitud ajax para obtener las horas reservadas
        $.get("/horas-reservadas/auditorio/" + fechaSeleccionada, function (data) {
            console.log("Horas agendadas:", data);

            disabledHoras = [];
            disabledHorasString = [];

            //Obtiene las horas deshabilitadas para el input 1 (hora reserva inicial)
            data.forEach((hora) => {
                // Se agrega cada rango de hora reservada al arreglo
                disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
            });

            console.log("horas reservadas ese día", disabledHoras);

            // Obtiene las horas deshabilitadas para la hora final de reserva
            disabledHoras.forEach((rango) => {
                disabledHorasString.push([rango[0], rango[1]]);

                const inicioRango = stringToDate(rango[0]);
                const finRango = stringToDate(rango[1]);

                horasInicialesDate.forEach((horaD) => {
                    if (horaD >= inicioRango && horaD <= finRango) {
                        reservada = true;
                        rangoHorasReservadas.push(horaD);
                    }
                });

                rangoHorasReservadas.shift();
                horasFinalesSeleccionable.push(rangoHorasReservadas);
                rangoHorasReservadas = [];
            });

            //Convierte las horas finales para reservar de tipo Date a string
            datesToString(horasFinalesSeleccionable);

            //Se deshabilitan las horas ya agendadas para ese día en el input de hora inicial
            $("#hora_reserva_inicio_auditorio").timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });

            //Se quita el mensaje de carga
            $("#pantallaCargaAuditorio").addClass("hidden");

            //Se vuelve a habilitar el input de la fecha
            diaReservaAuditorio.prop("disabled", false);

            //Se habilita la hora de reserva inicial
            $("#hora_reserva_inicio_auditorio").prop("disabled", false);

            //Cuando el usuario selecciona una hora de reserva inicial
            //Se activa el input de hora_reserva_final_auditorio
            $("#hora_reserva_inicio_auditorio").on("change", function () {
                //Limpia la hora reserva final
                $("#hora_reserva_final_auditorio").val("");

                let horaSelDate = stringToDate($("#hora_reserva_inicio_auditorio").val());

                //Limpieza de variables para cuando se seleccionen otras fechas
                let postDisabledHours = "";
                let tempDisHours = "";
                let pastHours = "";
                let pastHours2 = "";
                let indiceHora = "";
                let horasDisables2 = "";
                let finalDisablesHours = "";

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

                //Deshabilita las horas ya pasadas de la hora de inicio seleccionada
                pastHours = disablePastHoursString($("#hora_reserva_inicio_auditorio").val()); //Regresa un array de string de las horas pasadas a la seleccionada
                pastHours.push(horaSelDate); // Agrega la hora seleccionada como hora pasada
                pastHours2 = datesObjectsToString(pastHours); //Convierte ese array de strings a un array de tipo Date para hacer comparaciones de tiempo
                indiceHora = obtenerIndiceHora(
                    $("#hora_reserva_inicio_auditorio").val(),
                    horasAgendablesArray
                ); //Obtiene el indice de la siguiente hora
                pastHours2.push(siguienteHora(indiceHora)); //Agrega la siguiente hora de la hora seleccionada para deshabilitarla
                horasDisables2 = firstAndLast(pastHours2); //Regresa el rango con las horas deshabilitadas pasadas
                finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene las horas pasadas en un arreglo de cadenas

                finalDisablesHours.push(postDisabledHours); //Se unen las horas deshabilitadas despues de una agenda
                finalDisablesHours.push(horasDisables2); //Se unen las horas agendadas con las horas deshabiliadas

                //Se configura el input y las horas en las que el usuario puede terminar su reservación
                $("#hora_reserva_final_auditorio").timepicker({
                    minTime: "7:00am",
                    maxTime: "9:01pm",
                    step: 30,
                    timeFormat: "H:i",
                    showDuration: true,
                    durationTime: $("#hora_reserva_inicio_auditorio").val(),
                    scrollDefault: $("#hora_reserva_inicio_auditorio").val(),
                    disableTimeRanges: finalDisablesHours,
                });

                //Se meustra el input de horas 2
                if ($("#hora_reserva_inicio_auditorio").val()) {
                    $("#hora_reserva_final_auditorio").prop("disabled", false);
                }
            });
        });
    });

    //Aula magna cuando el usuario elija un día para reservar
    diaReservaMagna.on("change", function () {
        let fechaSeleccionada = $(this).val(); //Fecha seleccionada por el usuario en el calendario

        // Se deshabilita el input de la fecha y horas para que el usuario no haga otra solicitud
        // hasta que se termine la primera
        $(this).prop("disabled", true);
        $("#hora_reserva_inicio_magna").prop("disabled", true);
        $("#hora_reserva_final_magna").prop("disabled", true);

        // Se limpian los inputs de las horas
        $("#hora_reserva_inicio_magna").val("");
        $("#hora_reserva_final_magna").val("");

        //Muestra mensaje de que se estan cargando las horas reservadas
        $("#pantallaCargaMagna").removeClass("hidden");

        //Se hace la solicitud ajax para obtener las horas reservadas
        $.get("/horas-reservadas/aula-magna/" + fechaSeleccionada, function (data) {
            console.log("Horas agendadas:", data);

            disabledHoras = [];
            disabledHorasString = [];

            //Obtiene las horas deshabilitadas para el input 1 (hora reserva inicial)
            data.forEach((hora) => {
                // Se agrega cada rango de hora reservada al arreglo
                disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
            });

            console.log("horas reservadas ese día", disabledHoras);

            // Obtiene las horas deshabilitadas para la hora final de reserva
            disabledHoras.forEach((rango) => {
                disabledHorasString.push([rango[0], rango[1]]);

                const inicioRango = stringToDate(rango[0]);
                const finRango = stringToDate(rango[1]);

                horasInicialesDate.forEach((horaD) => {
                    if (horaD >= inicioRango && horaD <= finRango) {
                        reservada = true;
                        rangoHorasReservadas.push(horaD);
                    }
                });

                rangoHorasReservadas.shift();
                horasFinalesSeleccionable.push(rangoHorasReservadas);
                rangoHorasReservadas = [];
            });

            //Convierte las horas finales para reservar de tipo Date a string
            datesToString(horasFinalesSeleccionable);

            //Se deshabilitan las horas ya agendadas para ese día en el input de hora inicial
            $("#hora_reserva_inicio_magna").timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });

            //Se quita el mensaje de carga
            $("#pantallaCargaMagna").addClass("hidden");

            //Se vuelve a habilitar el input de la fecha
            diaReservaMagna.prop("disabled", false);

            //Se habilita la hora de reserva inicial
            $("#hora_reserva_inicio_magna").prop("disabled", false);

            //Cuando el usuario selecciona una hora de reserva inicial
            //Se activa el input de hora_reserva_final_magna
            $("#hora_reserva_inicio_magna").on("change", function () {
                //Limpia la hora reserva final
                $("#hora_reserva_final_magna").val("");

                let horaSelDate = stringToDate($("#hora_reserva_inicio_magna").val());

                //Limpieza de variables para cuando se seleccionen otras fechas
                let postDisabledHours = "";
                let tempDisHours = "";
                let pastHours = "";
                let pastHours2 = "";
                let indiceHora = "";
                let horasDisables2 = "";
                let finalDisablesHours = "";

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

                //Deshabilita las horas ya pasadas de la hora de inicio seleccionada
                pastHours = disablePastHoursString($("#hora_reserva_inicio_magna").val()); //Regresa un array de string de las horas pasadas a la seleccionada
                pastHours.push(horaSelDate); // Agrega la hora seleccionada como hora pasada
                pastHours2 = datesObjectsToString(pastHours); //Convierte ese array de strings a un array de tipo Date para hacer comparaciones de tiempo
                indiceHora = obtenerIndiceHora(
                    $("#hora_reserva_inicio_magna").val(),
                    horasAgendablesArray
                ); //Obtiene el indice de la siguiente hora
                pastHours2.push(siguienteHora(indiceHora)); //Agrega la siguiente hora de la hora seleccionada para deshabilitarla
                horasDisables2 = firstAndLast(pastHours2); //Regresa el rango con las horas deshabilitadas pasadas
                finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene las horas pasadas en un arreglo de cadenas

                finalDisablesHours.push(postDisabledHours); //Se unen las horas deshabilitadas despues de una agenda
                finalDisablesHours.push(horasDisables2); //Se unen las horas agendadas con las horas deshabiliadas

                //Se configura el input y las horas en las que el usuario puede terminar su reservación
                $("#hora_reserva_final_magna").timepicker({
                    minTime: "7:00am",
                    maxTime: "9:01pm",
                    step: 30,
                    timeFormat: "H:i",
                    showDuration: true,
                    durationTime: $("#hora_reserva_inicio_magna").val(),
                    scrollDefault: $("#hora_reserva_inicio_magna").val(),
                    disableTimeRanges: finalDisablesHours,
                });

                //Se meustra el input de horas 2
                if ($("#hora_reserva_inicio_magna").val()) {
                    $("#hora_reserva_final_magna").prop("disabled", false);
                }
            });
        });
    });

    diaReservaSalaVideo.on("change", function () {
        let fechaSeleccionada = $(this).val(); //Fecha seleccionada por el usuario en el calendario

        // Se deshabilita el input de la fecha y horas para que el usuario no haga otra solicitud
        // hasta que se termine la primera
        $(this).prop("disabled", true);
        $("#hora_reserva_inicio_sala_video").prop("disabled", true);
        $("#hora_reserva_final_sala_video").prop("disabled", true);

        // Se limpian los inputs de las horas
        $("#hora_reserva_inicio_sala_video").val("");
        $("#hora_reserva_final_sala_video").val("");

        //Muestra mensaje de que se estan cargando las horas reservadas
        $("#pantallaCargaSalaVideo").removeClass("hidden");

        //Se hace la solicitud ajax para obtener las horas reservadas
        $.get("/horas-reservadas/sala-videoconferencias/" + fechaSeleccionada, function (data) {
            console.log("Horas agendadas:", data);

            disabledHoras = [];
            disabledHorasString = [];

            //Obtiene las horas deshabilitadas para el input 1 (hora reserva inicial)
            data.forEach((hora) => {
                // Se agrega cada rango de hora reservada al arreglo
                disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
            });

            console.log("horas reservadas ese día", disabledHoras);

            // Obtiene las horas deshabilitadas para la hora final de reserva
            disabledHoras.forEach((rango) => {
                disabledHorasString.push([rango[0], rango[1]]);

                const inicioRango = stringToDate(rango[0]);
                const finRango = stringToDate(rango[1]);

                horasInicialesDate.forEach((horaD) => {
                    if (horaD >= inicioRango && horaD <= finRango) {
                        reservada = true;
                        rangoHorasReservadas.push(horaD);
                    }
                });

                rangoHorasReservadas.shift();
                horasFinalesSeleccionable.push(rangoHorasReservadas);
                rangoHorasReservadas = [];
            });

            //Convierte las horas finales para reservar de tipo Date a string
            datesToString(horasFinalesSeleccionable);

            //Se deshabilitan las horas ya agendadas para ese día en el input de hora inicial
            $("#hora_reserva_inicio_sala_video").timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });

            //Se quita el mensaje de carga
            $("#pantallaCargaSalaVideo").addClass("hidden");

            //Se vuelve a habilitar el input de la fecha
            diaReservaSalaVideo.prop("disabled", false);

            //Se habilita la hora de reserva inicial
            $("#hora_reserva_inicio_sala_video").prop("disabled", false);

            //Cuando el usuario selecciona una hora de reserva inicial
            //Se activa el input de hora_reserva_final_sala_video
            $("#hora_reserva_inicio_sala_video").on("change", function () {
                //Limpia la hora reserva final
                $("#hora_reserva_final_sala_video").val("");

                let horaSelDate = stringToDate($("#hora_reserva_inicio_sala_video").val());

                //Limpieza de variables para cuando se seleccionen otras fechas
                let postDisabledHours = "";
                let tempDisHours = "";
                let pastHours = "";
                let pastHours2 = "";
                let indiceHora = "";
                let horasDisables2 = "";
                let finalDisablesHours = "";

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

                //Deshabilita las horas ya pasadas de la hora de inicio seleccionada
                pastHours = disablePastHoursString($("#hora_reserva_inicio_sala_video").val()); //Regresa un array de string de las horas pasadas a la seleccionada
                pastHours.push(horaSelDate); // Agrega la hora seleccionada como hora pasada
                pastHours2 = datesObjectsToString(pastHours); //Convierte ese array de strings a un array de tipo Date para hacer comparaciones de tiempo
                indiceHora = obtenerIndiceHora(
                    $("#hora_reserva_inicio_sala_video").val(),
                    horasAgendablesArray
                ); //Obtiene el indice de la siguiente hora
                pastHours2.push(siguienteHora(indiceHora)); //Agrega la siguiente hora de la hora seleccionada para deshabilitarla
                horasDisables2 = firstAndLast(pastHours2); //Regresa el rango con las horas deshabilitadas pasadas
                finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene las horas pasadas en un arreglo de cadenas

                finalDisablesHours.push(postDisabledHours); //Se unen las horas deshabilitadas despues de una agenda
                finalDisablesHours.push(horasDisables2); //Se unen las horas agendadas con las horas deshabiliadas

                //Se configura el input y las horas en las que el usuario puede terminar su reservación
                $("#hora_reserva_final_sala_video").timepicker({
                    minTime: "7:00am",
                    maxTime: "9:01pm",
                    step: 30,
                    timeFormat: "H:i",
                    showDuration: true,
                    durationTime: $("#hora_reserva_inicio_sala_video").val(),
                    scrollDefault: $("#hora_reserva_inicio_sala_video").val(),
                    disableTimeRanges: finalDisablesHours,
                });

                //Se meustra el input de horas 2
                if ($("#hora_reserva_inicio_sala_video").val()) {
                    $("#hora_reserva_final_sala_video").prop("disabled", false);
                }
            });
        });
    });



    // Muestra el formulario seleccionado y esconde los demás
    $("#radioContainer").on("click", function (event) {
        var target = event.target;
        console.dir(target);

        hideAllForms();
        if (
            (target.type === "radio" && target.id === "radioAuditorio") ||
            target.htmlFor === "radioAuditorio"
        ) {
            $("#formAuditorio").removeClass("hidden");
        }
        if (
            (target.type === "radio" && target.id === "radioLab") ||
            target.htmlFor === "radioLab"
        ) {
            $("#formularioLabComp").removeClass("hidden");
        }
        if (
            (target.type === "radio" && target.id === "radioAulaMagna") ||
            target.htmlFor === "radioAulaMagna"
        ) {
            $("#formAulaMagna").removeClass("hidden");
        }
        if (
            (target.type === "radio" && target.id === "formularioLabComp") ||
            target.htmlFor === "formularioLabComp"
        ) {
            $("#formularioLabComp").removeClass("hidden");
        }
        if (
            (target.type === "radio" && target.id === "radioSalaV") ||
            target.htmlFor === "radioSalaV"
        ) {
            $("#formSalaV").removeClass("hidden");
        }
    });

    // Esconde todos los formularios
    function hideAllForms() {
        var formularios = document.querySelectorAll(".formulario");
        formularios.forEach(function (formulario) {
            formulario.classList.add("hidden");
        });
    }

    // Función para convertir una cadena (string) hora a un objeto de tipo Date
    function stringToDate(hourString) {
        //Se obtiene el día de hoy por default para hacer las comparaciones de la hora *** (quiza cambiar por dia seleccionado)
        const [hours, minutes] = hourString.split(":").map(Number);
        return new Date(
            date.getFullYear(),
            date.getMonth(),
            date.getDay(),
            hours,
            minutes
        );
    }

    function datesObjectsToString(dates) {
        let tempArray = [];
        for (let i = 0; i < dates.length; i++) {
            const hora = dates[i];
            tempArray.push(singleDateToString(hora));
        }
        return tempArray;
    }

    // Convierte un arreglo de horas tipo Date a uno de tipo String
    function datesToString(hourDate) {
        let tempArray = [];

        for (let i = 0; i < hourDate.length; i++) {
            for (let j = 0; j < hourDate[i].length; j++) {
                const element = hourDate[i][j];
                let horaStringTemp = element.getHours();
                horaStringTemp = horaStringTemp + ":00";
                tempArray.push(horaStringTemp);
            }
            disabledHorasFinales.push(tempArray);
            tempArray = [];
        }
    }

    // Convierte una hora de tipo Date a uno de tipo String
    function singleDateToString(hourDate) {
        let horaStringTemp = hourDate.getHours();
        //Sí es media hora
        if (hourDate.getMinutes() == 30) {
            horaStringTemp = horaStringTemp + ":30";
        } //Si es hora completa
        else {
            horaStringTemp = horaStringTemp + ":00";
        }
        return horaStringTemp;
    }

    //Deshabilita las horas pasadas de la hora inicial que el usuario selecciono
    function disablePastHoursString($horaInicial) {
        let tempArray = [];
        let horaI = stringToDate($horaInicial);
        for (let i = 0; i < horasInicialesDate.length; i++) {
            if (horasInicialesDate[i] < horaI) {
                tempArray.push(horasInicialesDate[i]);
            }
        }
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

    //Devuelve el primer y último elemento de un arreglo en otro arreglo
    function firstAndLast(array) {
        let tempArr = [];
        for (let i = 0; i < array.length; i++) {
            if (i === 0 || i === array.length - 1) {
                tempArr.push(array[i]);
            }
        }
        return tempArr;
    }

    function obtenerIndiceHora(hora, horasAgendablesArray) {
        // Iterar sobre el arreglo de horas agendables
        for (let i = 0; i < horasAgendablesArray.length; i++) {
            // Si la hora actual coincide con la hora buscada, devuelve el índice
            if (horasAgendablesArray[i] === hora) {
                return i;
            }
        }
        // Si la hora no se encuentra en el arreglo, devolver -1
        return -1;
    }

    // Regresa la siguiente hora en tipo String
    function siguienteHora(indiceHora) {
        //Dentro del rango de horas válidas
        if (indiceHora + 1 < horasAgendablesArray.length) {
            console.log(
                "La siguiente hora es:" + horasAgendablesArray[indiceHora + 1]
            );
            return horasAgendablesArray[indiceHora + 1];
        } else {
            //Si es la ultima hora
            console.log(
                "la siguiente hora es la primera hora de las horas válidas"
            );
            return horasAgendablesArray[0];
        }
    }

    // Regresa la siguiente media hora en tipo String
    function siguienteHora(indiceHora) {
        //Dentro del rango de horas válidas
        if (indiceHora + 1 < horasAgendablesArray.length) {
            console.log(
                "La siguiente hora es:" + horasAgendablesArray[indiceHora + 1]
            );
            return horasAgendablesArray[indiceHora + 1];
        } else {
            //Si es la ultima hora
            console.log(
                "la siguiente hora es la primera hora de las horas válidas"
            );
            return horasAgendablesArray[0];
        }
    }

    // Regresa la hora anterior en tipo String
    function anteriorHora(indiceHora) {
        //Dentro del rango de horas válidas
        if (indiceHora - 1 > 0) {
            console.log(
                "La siguiente hora es:" + horasAgendablesArray[indiceHora + 1]
            );
            return horasAgendablesArray[indiceHora + 1];
        } else {
            //Si es la ultima hora
            console.log(
                "la hora anterior a esta es ultima hora de las horas válidas"
            );
            return horasAgendablesArray[horasAgendablesArray.length - 1];
        }
    }

    //Regresa la siguiente hora en Tipo Date
    function siguienteHoraDate(indiceHora) {
        //Dentro del rango de horas válidas
        if (indiceHora + 1 < horasAgendablesArray.length) {
            console.log(
                "La siguiente hora es:" + horasAgendablesArray[indiceHora + 1]
            );
            let horaDate = stringToDate(horasAgendablesArray[indiceHora + 1]);
            console.log(horaDate);
            return horasAgendablesArray[indiceHora + 1];
        } else {
            //Si es la ultima hora
            console.log(
                "la siguiente hora es la primera hora de las horas válidas"
            );
            return horasAgendablesArray[0];
        }
    }

    // Obtiene los rangos deshabilitados para el input 2
    function obtenRangosDeshabilitados() {
        let rangosDeshabiliadosTemp = [];
        let rangosHelper = [];
        let rangoTemp = [];
        // Obtiene el rango de horas deshabilitadas
        disabledHorasString.forEach((rango) => {
            rangosDeshabiliadosTemp.push(
                obtenerRangoHoras(rango[0], rango[1], horasAgendablesArray)
            );
        });
        console.log(rangosDeshabiliadosTemp);
        rangosDeshabiliadosTemp.forEach((rango) => {
            //Si se quiere reservar solo 1 hora => ['09:00', '10:00']
            if (rango.length == 2) {
                rangosHelper.push([
                    rango[1],
                    siguienteHora(horasAgendablesArray.indexOf(rango[1])),
                ]);
            }
            //Si se quiere reservar más de 1 hora => ['09:00', '10:00', '11:00', '12:00']
            if (rango.length > 2) {
                rangoTemp = [...rango];
                rangoTemp.shift();
                rangosHelper.push(firstAndLast(rangoTemp));
            }
        });

        console.log("resultados: " + rangosHelper + rangoTemp);
        return rangosHelper;
    }

    // Toma como parametro la hora inicial y final de reservación y regresa un arreglo con el rango de horas reservadas
    // horaInicial = '08:00', horaFinal = '11:00' => ['08:00', '09:00', '10:00', '11:00']
    function obtenerRangoHoras(horaInicial, horaFinal, arregloHoras) {
        // Obtenemos los índices de la hora inicial y final
        let indiceInicial = arregloHoras.indexOf(horaInicial);
        let indiceFinal = arregloHoras.indexOf(horaFinal);

        // Verificamos si ambas horas están en el arreglo
        if (indiceInicial === -1 || indiceFinal === -1) {
            return [];
        }

        // Si la hora inicial es mayor que la hora final, intercambiamos los índices
        if (indiceInicial > indiceFinal) {
            [indiceInicial, indiceFinal] = [indiceFinal, indiceInicial];
        }

        // Creamos un subarreglo con las horas en el rango
        let rangoHoras = arregloHoras.slice(indiceInicial, indiceFinal + 1);

        return rangoHoras;
    }

    // Deshabilita la hora que el usuario ha seleccionado (hardcoded en la primera y ultima hora)
    function deshabilitaHoraSeleccionada(horaSeleccionada) {
        let horaSeleccDisabled;
        //Se es la primera hora
        if (horaSeleccionada === horasAgendablesArray[0]) {
            horaSeleccDisabled = ["06:00", "07:00"];
        }
        //Si es la última hora
        else if (
            horaSeleccionada ===
            horasAgendablesArray[horasAgendablesArray.length - 1]
        ) {
            horaSeleccDisabled = ["21:00", "22:00"];
        }
        //Si otra hora válida
        else {
            horaSeleccDisabled = [
                anteriorHora(horasAgendablesArray.indexOf(horaSeleccionada)),
                horaSeleccDisabled,
            ];
        }

        return horaSeleccDisabled;
    }
});

