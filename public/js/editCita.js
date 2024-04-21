$(function() {

    //Agrega caledario al input de fecha reservada
    flatpickr("#editFechaReservada", {
        defaultDate: $('#editFechaReservada').val(),
    });

    //Agrega la hora inicial reservada 
    $('#horaReservaInicio').timepicker({
        'step' : 30,
        timeFormat: 'H:i',
    }); 


});
