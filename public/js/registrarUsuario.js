$(function() {
    //Boton para cerrar la alerta que aparece cuando se edita una reservación
    $('#close-btn').on('click', function (e) {
        $(this).parent().remove();
    });
});