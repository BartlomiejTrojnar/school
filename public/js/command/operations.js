// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 ------------------------ //
// ------------------------- wydarzenia na stronie wyświetlania zadań -------------------------- //

// ----------------------------------- zarządzanie zadaniami ----------------------------------- //
function refreshRow(id, type, lp=0) {  // odświeżenie wiersza z zadaniem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/command/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(type=="add"){
                $('tr.create').before(result);
                $('tr[data-command_id='+id+']').show(1000);
                $('#showCreateRow').show();
            }
            else {
                $.when( $('tr.editRow[data-command_id='+id+']').hide(1000) ).then(function() {
                    $('tr.editRow[data-command_id='+id+']').remove();
                    $('tr[data-command_id='+id+']').replaceWith(result);
                    $('tr[data-command_id='+id+']').show(1000);
                });
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="8">Błąd odświeżania wiersza polecenia!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $('#commands').animate({width: '1400px'}, 500);
        $(this).hide();
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/polecenie/create",
        success: function(result) {
            $('#commands').append(result);
            $('tr#createRow').animate({opacity: '100%'}, 2000);
        },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem dodawania polecenia.</td></tr>';
            $('#commands tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania polecenia
    $('#commands').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(1000) ).then( function() {
            $('#createRow').remove();
            $('#showCreateRow').show(1000);
        });
    });

    $('#commands').delegate('#add', 'click', function() {
        add();
        $.when( $('#createRow').hide(1000) ).then( function() {
            $('#createRow').remove();
            $('#showCreateRow').show(1000);
        });
    });
}

function add() {   // zapisanie zadania w bazie danych
    var task_id      = $('#createRow input[name="task_id"]').val();
    var number      = $('#createRow input[name="number"]').val();
    var command     = $('#createRow input[name="command"]').val();
    var description = $('#createRow input[name="description"]').val();
    var points      = $('#createRow input[name="points"]').val();
    var lp = $('input[name="lp"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/polecenie",
        data: { task_id: task_id, number: number, command: command, description: description, points: points },
        success: function(id) {  refreshRow(id, 'add', lp);  },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Nie można dodać polecenia!</p></td></tr>';
            $('#commands tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania polecenia
    $('#commands').delegate('button.edit', 'click', function() {
        var id = $(this).data('command_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/polecenie/"+id+"/edit",
            data: { id: id },
            success: function(result) {
                $('tr[data-command_id='+id+']').before(result).hide();
                updateClick(lp);
            },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem modyfikowania polecenia.</td></tr>';
                $('tr[data-command_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania polecenia
    $('.cancelUpdate').click(function() {
        var id = $(this).data('command_id');
        $.when( $('.editRow[data-command_id='+id+']').hide(1000) ).then(function() {
            $('.editRow[data-command_id='+id+']').remove();
            $('tr[data-command_id='+id+']').show(1000);
        });
    });

    $('.update').click(function(){
        var id = $(this).data('command_id');
        update(id, lp);
        $.when( $('.editRow[data-command_id='+id+']').hide(1000) ).then(function() {
            $('.editRow[data-command_id='+id+']').remove();
            $('tr[data-command_id='+id+']').show(1000);
        });
    });
}

function update(id, lp) {   // zapisanie zmian polecenia w bazie danych
    var task_id     = $('tr[data-command_id='+id+'] input[name="task_id"]').val();
    var number      = $('tr[data-command_id='+id+'] input[name="number"]').val();
    var command     = $('tr[data-command_id='+id+'] input[name="command"]').val();
    var description = $('tr[data-command_id='+id+'] input[name="description"]').val();
    var points      = $('tr[data-command_id='+id+'] input[name="points"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/polecenie/"+id,
        data: { id: id, task_id: task_id, number: number, command: command, description: description, points: points },
        success: function() { refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Nie można zmienić danych polecenia!</td></tr>';
            $('tr[data-command_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie zadania (z bazy danych)
    $('#commands').delegate('.destroy', 'click', function() {
        var id = $(this).data('command_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/polecenie/" + id,
            success: function() {
                $.when( $('tr[data-command_id='+id+']').hide(1000) ).then(function() {
                    $('tr[data-command_id='+id+']').remove();
                });
            },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Nie można usunąć polecenia.</td></tr>';
                $('tr[data-command_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});