const checkLabComp = $('#checkboxLabComp');
const diaReservar = $('#fechaReservada');
const horaReservaInicio = $('#horaReservaInicio');
const horaReservaFinal = $('#horaReservaFinal');
const horasReservadas  = [];
const disabledHoras = [];

console.log(checkLabComp);

$(function(){
  $('#horaReservaInicio').timepicker({
    'minTime': '7:00am',
	  'maxTime': '9:01pm',
    'disableTimeRanges': [
      ['12am', '7am'],
      ['9:01pm', '11:31pm']
    ]
  }); 
  $('#horaReservaFinal').timepicker({
    'minTime': '7:00am',
	  'maxTime': '9:01pm',
    'disableTimeRanges': [
      ['12am', '7am'],
      ['9:01pm', '11:31pm']
    ]
  }); 
});

// Cuando el usuario elija un día para reservar
diaReservar.on('change', function(){
  let fechaSeleccionada = $(this).val();
  console.log('Día seleccionado', fechaSeleccionada);

  // Se deshabilita el input de la fecha para que el usuario no haga otra solicitud
  // hasta que se termine la primera
  $(this).prop('disabled', true);

  //Muestra mensaje de que se estan cargando las horas reservadas
  $('#pantallaCarga').removeClass('hidden');

  //Se hace la solicitud ajax para obtener las horas reservadas
  $.get('/horas-agendadas/' + fechaSeleccionada, function(data){
    console.log('Horas agendadaas:', data);

    data.map((hora) => {
      const newParHoras = [];
      newParHoras.push(hora.horaReservaInicio);
      newParHoras.push(hora.horaReservaFinal);
      horasReservadas.push(newParHoras);
    });

    console.log('horas reservadas ese día', horasReservadas);

    //Se quita el mensaje de carga
    $('#pantallaCarga').addClass('hidden');
    
    //Se vuelve a habilitar el input de la fecha
    diaReservar.prop('disabled', false);
  });
});