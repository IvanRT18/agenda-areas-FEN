const diasArray = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
let disabledHoras = [];
let disabledHorasRange = [];
let horasInicialesArray = ['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'];
let horasInicialesMediasArray = ['07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00'];
let horasMediasIterables = [...horasInicialesMediasArray];

let RangodisabledHoras = [];
let rangoHorasReservadas = [];
let horasInicialesDate = [];
let horasFinalesSeleccionable = [];
let disabledHorasFinales = [];
let mediaHoras = 0;
let horasClassObj = [];
let horasLibres = [];

//Se obtiene el dia de hoy
const date = new Date();
let fullDiaActual = date.toJSON().slice(0, 10);
// console.log(fullDiaActual); // "yyyy-mm-dd"

class HoraReserva {
    constructor(horas, actividad, localizacion) {
        this.horas = horas;
        this.actividad = actividad;
        this.localizacion = localizacion;
    }

    getRangoHoras(arrayHoras){
        return obtenerRangoHoras(this.horas[0], this.horas[1], arrayHoras);
    }
}

class HoraLibre {
    constructor(horas, actividad, localizacion) {
        this.horas = horas;
        this.actividad = actividad;
        this.localizacion = localizacion;
    }

    getRangoHoras(arrayHoras){
        return obtenerRangoHoras(this.horas[0], this.horas[1], arrayHoras);
    }
}

const diaActual = diasArray[date.getDay()];
$('#diaReservaLab').text(diaActual); //Titulo que indica el dia que se estan consultando los horarios reservados (en este ejemplo se usa el dia de hoy)

//Obtiene las horas reservadas
$.get('/horas-agendadas/' + fullDiaActual, function(data){
    mediaHoras = 0; //Bandera para saber si se reservo en una media hora, de aqui dependera la manera en que se muestran las horas

    // console.log('Horas agendadas:', data);

    disabledHoras = [];

    data.forEach(hora => {
      // Se agrega cada rango de hora reservada al arreglo
      disabledHoras.push([hora.horaReservaInicio, hora.horaReservaFinal]);
      if (terminaEnTreinta(hora.horaReservaInicio) || terminaEnTreinta(hora.horaReservaFinal)) {
        mediaHoras = 1;
      }
      horasClassObj.push(new HoraReserva([hora.horaReservaInicio, hora.horaReservaFinal],hora.actividad,'parte posterior'));
      console.log(horasClassObj);
    });

    console.log('horas reservadas ese día', disabledHoras);
    console.log(mediaHoras);

    
    //Si hay una reservación que tiene una hora con la terminacion 30
    if (mediaHoras) {
        
        horasInicialesMediasArray.forEach((hora) => {
            horasInicialesDate.push(stringToDate(hora));
        });
        
        //Aquí se asignan las horas ocupadas
        horasClassObj.forEach(horaCls => {
            let rangeTemp = horaCls.getRangoHoras(horasInicialesMediasArray);
            horasMediasIterables = horasMediasIterables.filter(elemento => !rangeTemp.includes(elemento)); //Eliminar el rango de las horas medias iterables
        });

        //Aquí debe de ir un metodo para asignar a todas las horas libres un objeto y agregarlo a un array
        horasMediasIterables.forEach((horaLibre, index) => {
            if (index+1 <= horasMediasIterables.length) {
                horasClassObj.push(new HoraLibre([horasMediasIterables[index], horasMediasIterables[index+1]], 'libre', 'libre'));
            }
        });
        console.log(horasClassObj);

        //Aquí se debe de crear un array que sea la union del array de horas libre y el de horas reservadas

        // numeros.sort((a, b) => a - b);
        let del = horasClassObj.sort((a, b) => a.horas[0] - b.horas[0]);
        console.log(del);

        //Aqui va a haber un arreglo que tenga la union de horas libre y reservadas y se va a iterar para mostrar las reservaciones
        horasClassObj.forEach(horaCls => {
            if (horaCls.actividad === 'libre') {
                dibujaLibreHoraMediaReservada(horaCls.horas[0], horaCls.horas[1], horaCls.actividad);
            }
            else{
                dibujaBloqueHoraMediaReservada(horaCls.horas[0], horaCls.horas[1], horaCls.actividad);
            }
        });
    }
    else{
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
    }

    
    
});


// Función para convertir una cadena de hora en un objeto Date
function stringToDate(hourString) {
    const [hours, minutes] = hourString.split(':').map(Number);
    return new Date(date.getFullYear(), date.getMonth(), date.getDay(), hours, minutes);
}

function dateToString(hourDate) {
    let tempArray = [];
    
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

//Función que determina si una hora tiene la terminacion 30
function terminaEnTreinta(cadenaHora) {
    // Extraer los dos últimos caracteres de la cadena
    let ultimosDosCaracteres = cadenaHora.slice(-2);

    // Verificar si los dos últimos caracteres son "30" o "00"
    if (ultimosDosCaracteres === "30") {
        return 1;
    } else if (ultimosDosCaracteres === "00") {
        return 0;
    } else {
        // Si no es ni "30" ni "00", lanzar un error o devolver un valor por defecto
        // En este caso, devolver -1 podría indicar un valor inválido
        return -1;
    }
}

// Toma como parametro la hora inicial y final de reservación y regresa un arreglo con el rango de horas reservadas
// horaInicial = '08:00', horaFinal = '11:00' => ['08:00', '09:00', '10:00', '11:00'] 
function obtenerRangoHoras(horaInicial, horaFinal, arregloHoras) {
    // Obtenemos los índices de la hora inicial y final
    let indiceInicial = arregloHoras.indexOf(horaInicial);
    let indiceFinal = arregloHoras.indexOf(horaFinal);
  
    // Verificamos si ambas horas están en el arreglo
    if (indiceInicial === -1 || indiceFinal === -1) {
        return [];
    }
  
    // Si la hora inicial es mayor que la hora final, intercambiamos los índices
    if (indiceInicial > indiceFinal) {
        [indiceInicial, indiceFinal] = [indiceFinal, indiceInicial];
    }
  
    // Creamos un subarreglo con las horas en el rango
    let rangoHoras = arregloHoras.slice(indiceInicial, indiceFinal + 1);
  
    return rangoHoras;
}

// Filtra las horas reservadas de las horas disponibles
function filtrarHorasDisponibles(horasTotales, horasReservadas) {
    let horasDisponibles = horasTotales.filter(hora => !horasReservadas.includes(hora));
    return horasDisponibles;
}


function filtrarHoras(horasTotales, horasARetener) {
    // Encontrar los índices de las horas proporcionadas
    let indiceInicio = horasTotales.indexOf(horasARetener[0]);
    let indiceFin = horasTotales.indexOf(horasARetener[1]);

    // Si alguna de las horas no está en el arreglo, devolver el arreglo original
    if (indiceInicio === -1 || indiceFin === -1) {
        return horasTotales;
    }

    // Retener solo las horas antes y después de las horas proporcionadas
    let horasFiltradas = horasTotales.slice(0, indiceInicio + 1).concat(horasTotales.slice(indiceFin));

    return horasFiltradas;
}


// 
function dibujaBloqueHoraMediaReservada(horaInicial, horaFinal, actividad){
    $('#horasContainer').append(`<div class='bg-danger p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-row justify-content-between' id='horaLab-' style="height: 120px;"><div>${horaInicial} - ${horaFinal} hrs</div> <div> ${actividad} </div> </div>`)
}
 
function dibujaLibreHoraMediaReservada(horaInicial, horaFinal, actividad){
    $('#horasContainer').append(`<div class='bg-success p-3 mb-4 rounded-2 text-white fs-3 fw-semibold d-flex flex-row justify-content-between' id='horaLab-'><div>${horaInicial} - ${horaFinal} hrs</div> <div> ${actividad} </div> </div>`)
}




