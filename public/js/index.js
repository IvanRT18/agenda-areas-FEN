const checkLabComp = $('#checkboxLabComp');
const diaReservar = $('#fechaReservada');

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

  $(this).prop('disabled', true);

  //Muestra mensaje de que se estan cargando las horas reservadas
  $('#pantallaCarga').removeClass('hidden');

  //Se hace la solicitud ajax para obtener las horas reservadas
  $.get('/horas-agendadas/' + fechaSeleccionada, function(data){
    console.log('Horas agendadaas:', data);

    //Se quita el mensaje de carga
    $('#pantallaCarga').addClass('hidden');
    
    diaReservar.prop('disabled', false);
  });
});