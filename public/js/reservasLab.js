const diasArray = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
let disabledHoras = [];
let horasInicialesArray = ['07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00'];

let RangodisabledHoras = [];
let rangoHorasReservadas = [];
let horasInicialesDate = [];
let horasFinalesSeleccionable = [];
let disabledHorasFinales = [];

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


    horasInicialesArray.forEach((hora) => {
        horasInicialesDate.push(stringToDate(hora));
    });

    // Recorre el arreglo de horas reservadas y verifica si la hora actual está dentro de alguno de los rangos
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
    dateToString(horasFinalesSeleccionable);

    // Colorea las horas dependiendo de si están reservadas o no
    horasInicialesArray.forEach((hora, index) => {
        let reservada = false;
        const horaActual = stringToDate(hora);

        

        // //Crea el rango de horas ocupadas
        // Recorre el arreglo de horas reservadas y verifica si la hora actual está dentro de alguno de los rangos
        disabledHoras.forEach(rango => {
            const inicioRango = stringToDate(rango[0]);
            const finRango = stringToDate(rango[1]);
    
            if (horaActual >= inicioRango && horaActual < finRango) {
                // rangoHorasReservadas.push(horaActual);
                reservada = true;
            }
        });

        // Agrega la clase hora-reservada si la hora está reservada, o la clase hora-libre si no lo está
        const claseHora = reservada ? 'bg-danger' : 'bg-success';

        index === horasInicialesArray.length - 1 ? 
        $('#horasContainer').append(`<div class='${claseHora} p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-row justify-content-between' id='horaLab-${index}'><div>${horasInicialesArray[index]} - 22:00 hrs</div>`) : 
        $('#horasContainer').append(`<div class='${claseHora} p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-row justify-content-between' id='horaLab-${index}'><div>${horasInicialesArray[index]} - ${horasInicialesArray[index+1]} hrs</div>`)

        // if (claseHora == 'bg-danger') {
        //     $(`#horaLab-${index}`).append(`<div>${data[actividadIndex].actividad}</div>`);
        //     actividadIndex++;
        // }
    });
    
});


// Función para convertir una cadena de hora en un objeto Date
function stringToDate(hourString) {
    const [hours, minutes] = hourString.split(':').map(Number);
    return new Date(date.getFullYear(), date.getMonth(), date.getDay(), hours, minutes);
}

function dateToString(hourDate) {
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
 




