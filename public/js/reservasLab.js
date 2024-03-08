const diasArray = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
let disabledHoras = [];
let horasInicialesArray = ['7:00','8:00','9:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'];


//Se obtiene el dia de hoy
const date = new Date();
let fullDiaActual = date.toJSON().slice(0, 10);
console.log(fullDiaActual); // "yyyy-mm-dd"

// console.log(date.getDay());
// console.log(date.getMonth());
// console.log(date.getFullYear());

const diaActual = diasArray[date.getDay()];
$('#diaReservaLab').text(diaActual); //Titulo que indica el dia que se estan consultando los horarios reservados (en este ejemplo se usa el dia de hoy)

//Obtiene las horas reservadas
$.get('/horas-agendadas/' + fullDiaActual, function(data){
    console.log('Horas agendadas:', data);

    console.log(data);

    disabledHoras = [];

    data.forEach(hora => {
      // Se agrega cada rango de hora reservada al arreglo
      disabledHoras.push([hora.horaReservaInicio, hora.horaReservaFinal]);
    });

    console.log('horas reservadas ese día', disabledHoras);

    // Colorea las horas dependiendo de si están reservadas o no
    horasInicialesArray.forEach((hora, index) => {
        let reservada = false;

        const horaActual = stringToDate(hora);

        // Recorre el arreglo de horas reservadas y verifica si la hora actual está dentro de alguno de los rangos
        disabledHoras.forEach(rango => {
            const inicioRango = stringToDate(rango[0]);
            const finRango = stringToDate(rango[1]);
    
            if (horaActual >= inicioRango && horaActual < finRango) {
                reservada = true;
            }
        });

        // Agrega la clase hora-reservada si la hora está reservada, o la clase hora-libre si no lo está
        const claseHora = reservada ? 'hora-reservada' : 'hora-libre';

        $('#horasContainer').append(`<div class='${claseHora}'>${hora}hrs</div>`);
    });

    
});


// Función para convertir una cadena de hora en un objeto Date
function stringToDate(hourString) {
    const [hours, minutes] = hourString.split(':').map(Number);
    return new Date(date.getFullYear(), date.getMonth(), date.getDay(), hours, minutes);
}



// _________________

// let disabledHoras = [['07:00', '08:00'], ['14:00', '15:00'], ['17:00', '19:00'], ['08:00', '09:00']];
// let activeHoras = ['7:00','8:00','9:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'];



// activeHoras.forEach((hora, index) => {
//     let reservada = false;

//     disabledHoras.forEach(rango => {
//         if (hora >= rango[0] && hora < rango[1]) {
//             reservada = true;
//         }
//     });
//     const claseHora = reservada ? 'hora-reservada' : 'hora-libre';

//     console.log(claseHora);
// });

 




