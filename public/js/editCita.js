$(function() {
    //Boton para cerrar la alerta que aparece cuando se edita una reservación
    $('#close-btn').on('click', function (e) {
        $(this).parent().remove();
    })

    //Agrega caledario al input de fecha reservada
    flatpickr("#editFechaReservada", {
        defaultDate: $('#editFechaReservada').val(),
    });

    //Agrega plugin al input hora inicial 
    $('#hora_reserva_inicio').timepicker({
        'step' : 30,
        timeFormat: 'H:i',
    }); 

    //Agrega plugin al input hora final
     $('#hora_reserva_final').timepicker({
        'step' : 30,
        timeFormat: 'H:i',
    }); 
});
