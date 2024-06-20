$(function() {
    let horasReservadas = [];
    // Botón para cerrar la alerta que aparece cuando se edita una reservación
    $('#close-btn').on('click', function (e) {
        $(this).parent().remove();
    });

    // Agrega calendario al input de fecha reservada
    flatpickr("#editFechaReservada", {
        defaultDate: $('#editFechaReservada').val(),
    });

    // Agrega plugin al input hora inicial 
    $('#hora_reserva_inicio').timepicker({
        'step' : 30,
        timeFormat: 'H:i',
    }); 

    // Agrega plugin al input hora final
    $('#hora_reserva_final').timepicker({
        'step' : 30,
        timeFormat: 'H:i',
    });

    // Obtener el área específica de la URL
    let pathSegments = window.location.pathname.split('/');
    let area = pathSegments[pathSegments.length - 2]; // Obtiene la penúltima parte de la URL para saber en que área estamos
    let endpoint = '';
    
    if (area === 'laboratorio') {
        let ubicacionPrev = $('#ubicacion').val() === 'Adelante' ? 'adelante' : 'atras'; 
        endpoint =  '/horas-reservadas/' + area + "/" + ubicacionPrev + "/" + $('#editFechaReservada').val();
    }
    else{
        endpoint =  '/horas-reservadas/' + area + "/" + $('#editFechaReservada').val();
    }

    getDisabledHours(endpoint);
    changeDay();

    $('#ubicacion').on('change', function () {
        $('#hora_reserva_inicio').val("");
        $('#hora_reserva_final').val("");

        $('#fecha_reservada').prop('disabled', true);
        $('#hora_reserva_inicio').prop('disabled', true);
        $('#hora_reserva_final').prop('disabled', true);

        if ($('#ubicacion').val() === 'Adelante') {
            endpoint =  '/horas-reservadas/' + area + "/adelante/" + $('#editFechaReservada').val();
            changeDay();
            getDisabledHours(endpoint);
        }
        else{
            endpoint =  '/horas-reservadas/' + area + "/atras/" + $('#editFechaReservada').val();
            console.log(endpoint);
            changeDay();
            getDisabledHours(endpoint);
        }
    })

    // Realizar la llamada AJAX
    function getDisabledHours(endpoint) {
        //Desactiva momentaneamente los inputs mientras se hace la llamada AJAX
        $('#fecha_reservada').prop('disabled', true);
        $('#hora_reserva_inicio').prop('disabled', true);
        $('#hora_reserva_final').prop('disabled', true);
        $("#pantallaCarga").removeClass("hidden");

        let disabledHoras = [];
        $.get(endpoint, function (data) {
            //Obtiene las horas reservadas de ese día
            data.forEach((hora) => {
                // Se agrega cada rango de hora reservada al arreglo (excepto el rango de horas que se van a editar)
                if (hora.hora_reserva_inicio !== $('#hora_reserva_inicio').val() && hora.hora_reserva_final !== $('#hora_reserva_final').val() ) {
                    disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
                }
            });
    
            horasReservadas = [...disabledHoras];
            console.log('horas reservadas');
            console.log(endpoint);
    
            let finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene las horas para el segundo input
    
            //Obtiene las horas pasadas dependiedo de la hora inical elegida
            let horasPastTemp = generarHorasPasadas($('#hora_reserva_inicio').val());
            horasPastTemp.push(obtenerSiguienteMediaHora($('#hora_reserva_inicio').val())); //Agrega la hora seleccionada para que no se pueda usar como hora final
            finalDisablesHours.push(firstAndLast(horasPastTemp)); //Agrega el arreglo de horas pasadas
    
            //Se deshabilitan las horas ya agendadas para ese día en el input de hora inicial
            $("#hora_reserva_inicio").timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });
    
            // finalDisablesHours.push(firstAndLast(generarHorasPasadas($('#hora_reserva_inicio').val())));
            //Deshabilita las horas siguientes de una hora reservada
            horasReservadas.forEach(horaPair => {
                if (horaPair[0] > $('#hora_reserva_inicio').val()) {
                    finalDisablesHours.push(firstAndLast(generarHorasSiguientes(horaPair[1])));
                }
            });
    
            //Se deshabilitan las horas ya agendadas para ese día en el input de hora final
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
    
            //Cuando se edita la nueva hora de inicio
            $("#hora_reserva_inicio").on("change", function () {
                //Reinicia las horas de finalizacion de reservas
                finalDisablesHours = [];
                horasPastTemp = [];
    
                finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene denuevo las horas reservadas del dia para el segundo input
    
                //Obtiene las horas pasadas dependiedo de la hora inical elegida
                horasPastTemp = generarHorasPasadas($('#hora_reserva_inicio').val());
                horasPastTemp.push(obtenerSiguienteMediaHora($('#hora_reserva_inicio').val())); //Agrega la hora seleccionada para que no se pueda usar como hora final
                //Añade el rango de horas al arreglo total de horas deshabilitadas
                finalDisablesHours.push(firstAndLast(horasPastTemp));
    
    
                 //Deshabilita las horas siguientes de una hora reservada
                horasReservadas.forEach(horaPair => {
                    if (horaPair[0] > $('#hora_reserva_inicio').val()) {
                        finalDisablesHours.push(firstAndLast(generarHorasSiguientes(horaPair[1])));
                    }
                });
    
                $("#hora_reserva_final").val("");
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
            });
            
            //Activa los inputs mientras se hace la llamada AJAX
            $('#fecha_reservada').prop('disabled', false);
            $('#hora_reserva_inicio').prop('disabled', false);
            $('#hora_reserva_final').prop('disabled', false);
            $("#pantallaCarga").addClass("hidden");
        });
    }

    //Si el usuario cambia a otro día, se hace el fetch de las hora reservadas de ese día, y la misma configuracion de los inputs
   function changeDay() {
       $('#editFechaReservada').on('change', function () {
        // let ubicacionPrev = $('#ubicacion').val() === 'Adelante' ? 'adelante' : 'atras'; 
        // endpoint =  '/horas-reservadas/' + area + "/" + ubicacionPrev + "/" + $('#editFechaReservada').val();

        if (area === 'laboratorio') {
            let ubicacionPrev = $('#ubicacion').val() === 'Adelante' ? 'adelante' : 'atras'; 
            endpoint =  '/horas-reservadas/' + area + "/" + ubicacionPrev + "/" + $('#editFechaReservada').val();
        }
        else{
            endpoint =  '/horas-reservadas/' + area + "/" + $('#editFechaReservada').val();
        }

        console.log(endpoint);
        console.log('se cambio el dia');
        //Mensaje de carga
        $("#pantallaCarga").removeClass("hidden");
        //Se deshabilitan los inputs hasta que cargue las horas reservadas de ese día
        $("#hora_reserva_inicio").prop("disabled", true);
        $("#hora_reserva_final").prop("disabled", true);

        $('#hora_reserva_inicio').val("");
        $('#hora_reserva_final').val("");
        // endpoint =  '/horas-reservadas/' + area + "/" + $('#editFechaReservada').val();
        // Realizar la llamada AJAX
        $.get(endpoint, function (data) {
            let disabledHoras = [];

            //Obtiene las horas reservadas de ese día
            data.forEach((hora) => {
                // Se agrega cada rango de hora reservada al arreglo (excepto el rango de horas que se van a editar)
                if (hora.hora_reserva_inicio !== $('#hora_reserva_inicio').val() && hora.hora_reserva_final !== $('#hora_reserva_final').val() ) {
                    disabledHoras.push([hora.hora_reserva_inicio, hora.hora_reserva_final]);
                }
            });

            horasReservadas = [...disabledHoras];
            console.log(horasReservadas);

            let finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene las horas para el segundo input

            //Obtiene las horas pasadas dependiedo de la hora inical elegida
            let horasPastTemp = generarHorasPasadas($('#hora_reserva_inicio').val());
            horasPastTemp.push(obtenerSiguienteMediaHora($('#hora_reserva_inicio').val())); //Agrega la hora seleccionada para que no se pueda usar como hora final
            finalDisablesHours.push(firstAndLast(horasPastTemp)); //Agrega el arreglo de horas pasadas

            //Se quita el mensaje de carga
            $("#pantallaCarga").addClass("hidden");

            //Habilita de nuevo el input de hora inicio
            $("#hora_reserva_inicio").prop("disabled", false);

            //Se deshabilitan las horas ya agendadas para ese día en el input de hora inicial
            $("#hora_reserva_inicio").timepicker({
                minTime: "7:00am",
                maxTime: "9:01pm",
                step: 30,
                timeFormat: "H:i",
                disableTimeRanges: disabledHoras,
            });

            // finalDisablesHours.push(firstAndLast(generarHorasPasadas($('#hora_reserva_inicio').val())));
            //Deshabilita las horas siguientes de una hora reservada
            horasReservadas.forEach(horaPair => {
                if (horaPair[0] > $('#hora_reserva_inicio').val()) {
                    finalDisablesHours.push(firstAndLast(generarHorasSiguientes(horaPair[1])));
                }
            });

            //Se deshabilitan las horas ya agendadas para ese día en el input de hora final
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

            //Cuando se edita la nueva hora de inicio
            $("#hora_reserva_inicio").on("change", function () {
                //Reinicia las horas de finalizacion de reservas
                finalDisablesHours = [];
                horasPastTemp = [];

                finalDisablesHours = obtenRangosDeshabilitados(); //Obtiene de nuevo las horas reservadas del dia para el segundo input

                //Obtiene las horas pasadas dependiedo de la hora inical elegida
                horasPastTemp = generarHorasPasadas($('#hora_reserva_inicio').val());
                horasPastTemp.push(obtenerSiguienteMediaHora($('#hora_reserva_inicio').val())); //Agrega la hora seleccionada para que no se pueda usar como hora final
                //Añade el rango de horas al arreglo total de horas deshabilitadas
                finalDisablesHours.push(firstAndLast(horasPastTemp));

                //Deshabilita las horas siguientes de una hora reservada
                horasReservadas.forEach(horaPair => {
                    if (horaPair[0] > $('#hora_reserva_inicio').val()) {
                        finalDisablesHours.push(firstAndLast(generarHorasSiguientes(horaPair[1])));
                    }
                });

                //Habilita de nuevo la hora reservación final
                $("#hora_reserva_final").prop("disabled", false);
                
                $("#hora_reserva_final").val("");
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
            });
        });
    })
   }

    // Obtiene los rangos deshabilitados para el input 2 (hora final reservacion)
    function obtenRangosDeshabilitados() {
        let rangosDeshabiliadosTemp = [];
        horasReservadas.forEach(disabledHora => {
            rangosDeshabiliadosTemp.push(generarRangoHoras(disabledHora[0], disabledHora[1])); 
        });
        let rangosHelper = [];
        let rangoTemp = [];

        rangosDeshabiliadosTemp.forEach((rango) => {
            //Si se quiere reservar solo 1 hora => ['09:00', '10:00']
            if (rango.length == 2) {
                rangosHelper.push([obtenerSiguienteMediaHora(rango[1])]);
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

    function generarRangoHoras(horaInicial, horaFinal) {
        // Convertir las horas inicial y final a objetos Date
        let start = new Date(`1970-01-01T${horaInicial}:00`);
        let end = new Date(`1970-01-01T${horaFinal}:00`);
        let result = [];
    
        // Iterar desde la hora inicial hasta la hora final, incrementando cada 30 minutos
        while (start <= end) {
            // Formatear la hora actual y agregarla al resultado
            let horas = String(start.getHours()).padStart(2, '0');
            let minutos = String(start.getMinutes()).padStart(2, '0');
            result.push(`${horas}:${minutos}`);
    
            // Incrementar 30 minutos
            start.setMinutes(start.getMinutes() + 30);
        }
    
        return result;
    }

    function generarHorasPasadas(horaFinal) {
        // Hora inicial fija en '07:00'
        let horaInicial = '07:00';
        
        // Convertir las horas inicial y final a objetos Date
        let start = new Date(`1970-01-01T${horaInicial}:00`);
        let end = new Date(`1970-01-01T${horaFinal}:00`);
        let result = [];
    
        // Iterar desde la hora inicial hasta la hora final, incrementando cada 30 minutos
        while (start <= end) {
            // Formatear la hora actual y agregarla al resultado
            let horas = String(start.getHours()).padStart(2, '0');
            let minutos = String(start.getMinutes()).padStart(2, '0');
            result.push(`${horas}:${minutos}`);
    
            // Incrementar 30 minutos
            start.setMinutes(start.getMinutes() + 30);
        }
    
        return result;
    }

    function obtenerSiguienteMediaHora(horaActual) {
        // Convertir la hora actual a un objeto Date
        let currentTime = new Date(`1970-01-01T${horaActual}:00`);
    
        // Incrementar los minutos en 30
        currentTime.setMinutes(currentTime.getMinutes() + 30);
    
        // Formatear la hora y los minutos para que tengan dos dígitos
        let horas = String(currentTime.getHours()).padStart(2, '0');
        let minutos = String(currentTime.getMinutes()).padStart(2, '0');
    
        // Devolver la hora formateada
        return `${horas}:${minutos}`;
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

    function generarHorasSiguientes(horaInicial) {
        // Convertir la hora inicial a un objeto Date
        let start = new Date(`1970-01-01T${horaInicial}:00`);
        let result = [];
    
        // Definir el final del día
        let end = new Date('1970-01-01T23:59:00');
    
        // Iterar desde la hora inicial hasta el final del día, incrementando cada 30 minutos
        while (start <= end) {
            // Formatear la hora actual y agregarla al resultado
            let horas = String(start.getHours()).padStart(2, '0');
            let minutos = String(start.getMinutes()).padStart(2, '0');
            result.push(`${horas}:${minutos}`);
    
            // Incrementar 30 minutos
            start.setMinutes(start.getMinutes() + 30);
        }
    
        return result;
    }
});
