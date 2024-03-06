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

  $('pantallaCarga').removeClass('hidden');

  $.get('/horas-agendadas/' + fechaSeleccionada, function(data){
    console.log('Horas agendadaas:', data);

    $('pantallaCarga').addClass('hidden');

  });
});