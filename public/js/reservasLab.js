const diasArray = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
let disabledHoras = [];
let horasInicialesArray = ['7:00am','8:00am','9:00am','10:00am','11:00am','12:00pm','1:00pm','2:00pm','3:00pm','4:00pm','5:00pm','6:00pm','7:00pm','8:00pm','9:00pm'];


//Se obtiene el dia de hoy
const date = new Date();
let fullDiaActual = date.toJSON().slice(0, 10);
console.log(fullDiaActual); // "yyyy-mm-dd"

const diaActual = diasArray[date.getDay()];
$('#diaReservaLab').text(diaActual); //Titulo que indica el dia que se estan consultando los horarios reservados (en este ejemplo se usa el dia de hoy)

//Obtiene las horas reservadas
$.get('/horas-agendadas/' + fullDiaActual, function(data){
    console.log('Horas agendadas:', data);

    disabledHoras = [];

    data.forEach(hora => {
      // Se agrega cada rango de hora reservada al arreglo
      disabledHoras.push([hora.horaReservaInicio, hora.horaReservaFinal]);
    });

    console.log('horas reservadas ese día', disabledHoras);


    // Colorea las horas dependiendo de si están reservadas o no
    horasInicialesArray.forEach((hora, index) => {
        let reservada = false;

        // Recorre el arreglo de horas reservadas y verifica si la hora actual está dentro de alguno de los rangos
        disabledHoras.forEach(rango => {
            if (hora >= rango[0] && hora < rango[1]) {
                reservada = true;
            }
        });

        // Agrega la clase hora-reservada si la hora está reservada, o la clase hora-container si no lo está
        const claseHora = reservada ? 'hora-reservada' : 'hora-container';

        // Si no es la última hora, agrega el div con la clase correspondiente
        if (index < horasInicialesArray.length) {
            $('#horasContainer').append(`<div class='${claseHora}'>${hora}</div>`);
        } else {
            // Si es la última hora, solo agrega el div sin clase adicional
            $('#horasContainer').append(`<div>${hora}</div>`);
        }
    });
});




