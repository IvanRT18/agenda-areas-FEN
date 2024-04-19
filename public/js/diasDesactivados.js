let disabledDays = [];
flatpickr("#diasDeshabilitadosInput", {
    // mode: "multiple",
    inline: true,
    disable: [
        "2024-03-18", "2024-03-21", "2025-03-08",
        {
            from: "2024-04-01",
            to: "2024-04-20"
        },
        {
            from: "2024-09-01",
            to: "2024-12-01"
        }
    ]
  });


$.get('all-dias-inhabilitados-laboratorio', function(data){
    data.forEach(dias => {
        disabledDays.push(dias.diaDeshabiliado);
    });

    flatpickr("#agregarDiaDesactivadoInput", {
        mode: "multiple",
        inline: true,
        defaultDate: disabledDays,
    });
});




