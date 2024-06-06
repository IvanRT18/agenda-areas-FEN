let disabledDays = [];

$.get('deshabilitar-dias/obtener-dias-deshabilitados', function(data){
    data.forEach(dias => {
        disabledDays.push(dias.dias_deshabilitado);
    });
    
    console.log(disabledDays);
    flatpickr("#agregarDiaDesactivadoInput", {
        mode: "multiple",
        inline: true,
        defaultDate: disabledDays,
    });
});