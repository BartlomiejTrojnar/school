// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 ------------------------ //
// ------------------------- wydarzenia na stronie wyświetlania zadań -------------------------- //

// ----------------------------------- zarządzanie zadaniami ----------------------------------- //
function refreshRow(id, type, lp=0) {  // odświeżenie wiersza z zadaniem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/task/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(type=="add"){
                $('tr.create').before(result);
                $('tr[data-task_id='+id+']').show(1000);
                $('#showCreateRow').show();
            }
            else {
                $.when( $('tr.editRow[data-task_id='+id+']').hide(1000) ).then(function() {
                    $('tr.editRow[data-task_id='+id+']').remove();
                    $('tr[data-task_id='+id+']').replaceWith(result);
                    $('tr[data-task_id='+id+']').show(1000);
                });
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="10">Błąd odświeżania wiersza zadania!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide(1000);
        $('#tasks').animate({width: '1400px'}, 500);
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/zadanie/create",
        success: function(result) {  $('#tasks').append(result);  },
        error: function() {
            var error = '<tr><td colspan="10" class="error">Błąd tworzenia wiersza z formularzem dodawania zadania.</td></tr>';
            $('#tasks tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania zadania
    $('#tasks').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(1000) ).then( function() {
            $('#createRow').remove();
            $('#showCreateRow').show(1000);
        });
    });

    $('#tasks').delegate('#add', 'click', function() {
        add();
        $.when( $('#createRow').hide(1000) ).then( function() {
            $('#createRow').remove();
            $('#showCreateRow').show(1000);
        });
    });
}

function add() {   // zapisanie zadania w bazie danych
    var name        = $('#createRow input[name="name"]').val();
    var points      = $('#createRow input[name="points"]').val();
    var importance  = $('#createRow input[name="importance"]').val();
    var sheet_name  = $('#createRow input[name="sheet_name"]').val();
    var lp = $('input[name="lp"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/zadanie",
        data: { name: name, points: points, importance: importance, sheet_name: sheet_name },
        success: function(id) {  refreshRow(id, 'add', lp);  },
        error: function() {
            var error = '<tr><td colspan="10" class="error">Błąd dodawania zadania!</p></td></tr>';
            $('#tasks tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania zadania
    $('#tasks').delegate('button.edit', 'click', function() {
        var id = $(this).data('task_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/zadanie/"+id+"/edit",
            data: { id: id },
            success: function(result) {
                $('tr[data-task_id='+id+']').before(result).hide();
                updateClick(lp);
            },
            error: function() {
                var error = '<tr><td colspan="10" class="error">Błąd tworzenia wiersza z formularzem modyfikowania zadania.</td></tr>';
                $('tr[data-task_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania zadania
    $('.cancelUpdate').click(function() {
        var id = $(this).data('task_id');
        $.when( $('.editRow[data-task_id='+id+']').hide(1000) ).then(function() {
            $('.editRow[data-task_id='+id+']').remove();
            $('tr[data-task_id='+id+']').show(1000);
        });
    });

    $('.update').click(function(){
        var id = $(this).data('task_id');
        update(id, lp);
        $.when( $('.editRow[data-task_id='+id+']').hide(1000) ).then(function() {
            $('.editRow[data-task_id='+id+']').remove();
            $('tr[data-task_id='+id+']').show(1000);
        });
    });
}

function update(id, lp) {   // zapisanie zmian zadania w bazie danych
    var name        = $('tr[data-task_id='+id+'] input[name="name"]').val();
    var points      = $('tr[data-task_id='+id+'] input[name="points"]').val();
    var importance  = $('tr[data-task_id='+id+'] input[name="importance"]').val();
    var sheet_name  = $('tr[data-task_id='+id+'] input[name="sheet_name"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/zadanie/"+id,
        data: { id: id, name: name, points: points, importance: importance, sheet_name: sheet_name },
        success: function() { refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="10" class="error">Błąd modyfikowania zadania!</td></tr>';
            $('tr[data-task_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie zadania (z bazy danych)
    $('#tasks').delegate('.destroy', 'click', function() {
        var id = $(this).data('task_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/zadanie/" + id,
            success: function() {  $('tr[data-task_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="10" class="error">Nie można usunąć zadania.</td></tr>';
                $('tr[data-task_id='+id+']').after(error).hide();
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