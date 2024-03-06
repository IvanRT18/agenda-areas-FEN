const checkLabComp = $('#checkboxLabComp');
const diaReservar = $('#fechaReservada');
const horaReservaInicio = $('#horaReservaInicio');
const horaReservaFinal = $('#horaReservaFinal');
// const horasReservadas  = [];
let disabledHoras = [];

console.log(checkLabComp);

$(function(){
  
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

    disabledHoras = [];

    data.forEach(hora => {
      // Se agrega cada rango de hora reservada al arreglo
      disabledHoras.push([hora.horaReservaInicio, hora.horaReservaFinal]);
    });

    console.log('horas reservadas ese día', disabledHoras);


    $('#horaReservaInicio').timepicker({
      'minTime': '7:00am',
      'maxTime': '9:01pm',
      'disableTimeRanges': disabledHoras //Estoy tratando de agregar estas horas dinamicamente
    }); 
    $('#horaReservaFinal').timepicker({
      'minTime': '7:00am',
      'maxTime': '9:01pm',
      'disableTimeRanges': disabledHoras
    }); 

    // $('#horaReservaInicio', '#horaReservaFinal').timepicker('option', 'disabledTimeRanges', disabledHoras);
    
    // console.log($('#horaReservaInicio'));

    // console.log($('#horaReservaFinal'));

// Imprime en la consola las opciones actuales del plugin TimePicker
console.log($('#horaReservaInicio').timepicker('option'));

// Imprime en la consola las opciones actuales del plugin TimePicker
console.log($('#horaReservaFinal').timepicker('option'));


    //Se quita el mensaje de carga
    $('#pantallaCarga').addClass('hidden');
    
    //Se vuelve a habilitar el input de la fecha
    diaReservar.prop('disabled', false);
  });
});