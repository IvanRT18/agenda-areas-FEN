const horaInput = document.getElementById('time');
const fechaInput = document.getElementById('fecha');
const checkboxContainer = document.getElementById('checkboxContainer');
const checkboxAuditorio = document.getElementById('checkboxAuditorio');
const checkboxesAreas = document.querySelectorAll('.checkbox-container input[type="checkbox"] ');
const checkboxProyector = document.getElementById('usaProyector');

fechaInput.addEventListener('input', function(e){
  var day = new Date(this.value).getUTCDay();
  console.log(day);
  if(day === 0){
    e.preventDefault();
    this.value = '';
    alert('Domingos no disponibles para agendar');
  }
});

// for (const checkbox of checkboxesAreas) {
//     checkbox.addEventListener('checked', function (e) {
        
//     })
// }

console.log(horaInput);
console.log(fechaInput);
console.log(checkboxContainer);
// console.log(checkboxesAreas);
console.dir(checkboxAuditorio);
console.dir(checkboxProyector);