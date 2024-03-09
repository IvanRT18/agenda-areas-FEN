const checkLabComp = $('#checkboxLabComp');
const diaReservar = $('#fechaReservada');
const horaReservaInicio = $('#horaReservaInicio');
const horaReservaFinal = $('#horaReservaFinal');
// const horasReservadas  = [];
let disabledHoras = [];

console.log(checkLabComp);

$(function(){
  $('#horaReservaInicio').prop('disabled', true);
  $('#horaReservaFinal').prop('disabled', true);

  $('#horaReservaInicio').timepicker({
    'minTime': '7:00am',
    'maxTime': '9:01pm',
    timeFormat: 'H:i',
    // 'scrollDefault': 'now',
    'disableTimeRanges': disabledHoras //Estoy tratando de agregar estas horas dinamicamente
  }); 
  $('#horaReservaFinal').timepicker({
    'minTime': '7:00am',
    'maxTime': '9:01pm',
    timeFormat: 'H:i',
    // 'scrollDefault': 'now',
    'disableTimeRanges': disabledHoras
  }); 
});

// Cuando el usuario elija un día para reservar
diaReservar.on('change', function(){
  let fechaSeleccionada = $(this).val();
  console.log('Día seleccionado', fechaSeleccionada);

  // Se deshabilita el input de la fecha y hora para que el usuario no haga otra solicitud
  // hasta que se termine la primera
  $(this).prop('disabled', true);
  $('#horaReservaInicio').prop('disabled', true);
  $('#horaReservaFinal').prop('disabled', true);

  // Se limpian los inputs de las horas
  $('#horaReservaInicio').val('');
  $('#horaReservaFinal').val('');

  //Muestra mensaje de que se estan cargando las horas reservadas
  $('#pantallaCarga').removeClass('hidden');

  //Se hace la solicitud ajax para obtener las horas reservadas
  $.get('/horas-agendadas/' + fechaSeleccionada, function(data){
    console.log('Horas agendadas:', data);

    disabledHoras = [];

    data.forEach(hora => {
      // Se agrega cada rango de hora reservada al arreglo
      disabledHoras.push([hora.horaReservaInicio, hora.horaReservaFinal]);
    });

    console.log('horas reservadas ese día', disabledHoras);

    //Se deshabilitan las horas ya agendadas para ese día
    $('#horaReservaInicio').timepicker({
      'minTime': '7:00am',
      'maxTime': '9:01pm',
      'step' : 60,
      timeFormat: 'H:i',
      'disableTimeRanges': disabledHoras
    }); 

    //Se quita el mensaje de carga
    $('#pantallaCarga').addClass('hidden');
    
    //Se vuelve a habilitar el input de la fecha
    diaReservar.prop('disabled', false);
    
    //Se habilita la hora de reserva inicial
    $('#horaReservaInicio').prop('disabled', false);

    //Cuando el usuario selecciona una hora de reserva inicial
    //Se activa el input de horaReservaFinal
    $('#horaReservaInicio').on('change', function () {
      $('#horaReservaFinal').timepicker({
        'minTime': '7:00am',
        'maxTime': '9:01pm',
        'step' : 60,
        timeFormat: 'H:i',
        'showDuration': true,
        'durationTime':  $('#horaReservaInicio').val(),
        'scrollDefault': $('#horaReservaInicio').val(),
        'disableTimeRanges': disabledHoras
      }); 

      if ($('#horaReservaInicio').val()) {
        $('#horaReservaFinal').prop('disabled', false);
      }
    })
    
    
  });
});