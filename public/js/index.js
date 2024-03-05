const horaInput = document.getElementById('horaReservada');
const fechaInput = document.getElementById('fecha');
const checkboxContainer = document.getElementById('checkboxContainer');
const checkboxAuditorio = document.getElementById('checkboxAuditorio');
const checkboxesAreas = document.querySelectorAll('.checkbox-container input[type="checkbox"] ');
const checkboxProyector = document.getElementById('usaProyector');
const formContainer = document.getElementById('formularioLabComp');

// fechaInput.addEventListener('input', function(e){
//   var day = new Date(this.value).getUTCDay();
//   console.log(day);
//   if(day === 0){
//     e.preventDefault();
//     this.value = '';
//     alert('Domingos no disponibles para agendar');
//   }
// });

// for (const checkbox of checkboxesAreas) {
//     checkbox.addEventListener('checked', function (e) {
        
//     })
// }

console.log(horaInput);
// console.log(fechaInput);
// console.log(checkboxContainer);
// // console.log(checkboxesAreas);
// console.dir(checkboxAuditorio);
// console.dir(checkboxProyector);

// // initialize input widgets first
// $('#datepairExample .time').timepicker({
//   'showDuration': true,
//   'timeFormat': 'g:ia'
// });

// $('#datepairExample .date').datepicker({
//   'format': 'yyyy-m-d',
//   'autoclose': true
// });

// // initialize datepair
// $('#datepairExample').datepair();


// var container = document.getElementById('container')
// var datepair = new Datepair(container, options);


// $('#date').datepicker({
//   'format': 'yyyy-m-d',
//   'autoclose': true
// });

// $('#horaReservada').timepicker({
// 	'disableTimeRanges': [
// 		['1am', '7am'],
// 		['9pm', '12pm']
// 	]
// });

$(function(){
  $('#horaReservaInicio').timepicker(); 
  $('#horaReservaFinal').timepicker(); 
});

// const horaInput = $('#horaReservada');

console.log(horaInput.value);