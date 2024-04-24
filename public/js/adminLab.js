$(function() {
    $('th[data-sort]').on('click', function() {
        var table = $(this).parents('table').eq(0);
        var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
        this.asc = !this.asc;
        if (!this.asc) { rows = rows.reverse(); }
        for (var i = 0; i < rows.length; i++) { table.append(rows[i]); }
        // updateSortIcon($(this));
    });

    function comparer(index) {
        return function(a, b) {
            var valA = getCellValue(a, index), valB = getCellValue(b, index);
            return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
        };
    }

    function getCellValue(row, index){ return $(row).children('td').eq(index).text(); }

    function updateSortIcon(header) {
        const thead = header.closest('thead').find('.sort-icon').html('');
        console.log(thead);
        cheader.closest('thead').find('th').removeClass('asc');
        console.log();
        var icon = header.hasClass('asc') ? '<span class="sort-icon asc"></span>' : '<span class="sort-icon"></span>';
        header.addClass('asc').find('.sort-icon').html(icon);
    }


    //Boton para cerrar la alerta que aparece cuando se guarda una reservación
    $('#close-btn').on('click', function (e) {
        $(this).parent().remove();
    })
});
