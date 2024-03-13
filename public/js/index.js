const checkLabComp = $('#checkboxLabComp');
const diaReservar = $('#fechaReservada');
const horaReservaInicio = $('#horaReservaInicio');
const horaReservaFinal = $('#horaReservaFinal');
// const horasReservadas  = [];
let horasAgendablesArray = ['7:00','8:00','9:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'];

let horasInicialesDate = [];
let horasFinalesSeleccionable = [];
let rangoHorasReservadas = [];

let disabledHoras = []; //horas deshabilitadas para la hora de reserva inicial
let disabledHorasFinales = []; //horas deshabilitadas para la hora de reserva final
let pastHours = [] //Horas pasadas de la hora inicial de reserva que selecciono el usuario

//Se obtiene el dia de hoy
const date = new Date();
let fullDiaActual = date.toJSON().slice(0, 10);
console.log(fullDiaActual); // "yyyy-mm-dd"

//Convierte el arreglo de horas (string) validas para agendar a horas tipo Date
horasAgendablesArray.forEach((hora) => {
  horasInicialesDate.push(stringToDate(hora));
});

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

    //Obtiene las horas deshabilitadas para el input 1 (hora reserva inicial)
    data.forEach(hora => {
      // Se agrega cada rango de hora reservada al arreglo
      disabledHoras.push([hora.horaReservaInicio, hora.horaReservaFinal]);
    });

    console.log('horas reservadas ese día', disabledHoras);

     // Obtiene las horas deshabilitadas para la hora final de reserva
     disabledHoras.forEach(rango => {
      const inicioRango = stringToDate(rango[0]);
      const finRango = stringToDate(rango[1]);

      horasInicialesDate.forEach((horaD) => {
          if (horaD >= inicioRango && horaD <= finRango) {
              reservada = true;
              rangoHorasReservadas.push(horaD);
          }
      });

      rangoHorasReservadas.shift()
      horasFinalesSeleccionable.push(rangoHorasReservadas);
      rangoHorasReservadas = [];
  });

  //Convierte las horas finales para reservar de tipo Date a string
  datesToString(horasFinalesSeleccionable);


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
    //Limpia la hora reserva final
    $('#horaReservaFinal').val('');

    // console.log($('#horaReservaInicio').val());
    //Deshabilita las horas ya pasadas de la hora de inicio de reserva
    pastHours = disablePastHours($('#horaReservaInicio').val());
    let pastHours2 = datesObjectsToString(pastHours);
    console.log(pastHours2);

      $('#horaReservaFinal').timepicker({
        'minTime': '7:00am',
        'maxTime': '9:01pm',
        'step' : 60,
        timeFormat: 'H:i',
        'showDuration': true,
        'durationTime':  $('#horaReservaInicio').val(),
        'scrollDefault': $('#horaReservaInicio').val(),
        'disableTimeRanges': [pastHours2]
      }); 

      //Activa la hora de reserva final
      if ($('#horaReservaInicio').val()) {
        $('#horaReservaFinal').prop('disabled', false);
      }

      
    })
    
    
  });
});

// Función para convertir una cadena de hora en un objeto Date
function stringToDate(hourString) {
  const [hours, minutes] = hourString.split(':').map(Number);
  return new Date(date.getFullYear(), date.getMonth(), date.getDay(), hours, minutes);
}

function datesObjectsToString(dates) {
  let tempArray = [];
  for (let i = 0; i < dates.length; i++) {
    const hora = dates[i];
    tempArray.push(singleDateToString(hora));
  }
  return tempArray;
}

function datesToString(hourDate) {
  let tempArray = [];
  console.log('DatetoString function');
  
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

function singleDateToString(hourDate) {
  let horaStringTemp = hourDate.getHours();
  horaStringTemp = horaStringTemp + ":00";
  return horaStringTemp;
}

//Deshabilita las horas pasadas de la hora inicial que el usuario selecciono
function disablePastHours($horaInicial) {
  let tempArray = []
  let horaI = stringToDate($horaInicial);
  for (let i = 0; i < horasInicialesDate.length; i++) {
    if (horasInicialesDate[i] < horaI) {
      tempArray.push(horasInicialesDate[i]);
    }
  }
  return tempArray;
}