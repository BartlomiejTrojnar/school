// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.10.2021 ------------------------ //
// ------------------------- wydarzenia na stronie wyświetlania sesji -------------------------- //

// ------------------------------------ zarządzanie sesjami ------------------------------------ //
function refreshRow(id, version) {  // odświeżenie wiersza z sesją o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/session/refreshRow",
        data: { id: id },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-session_id='+id+']').remove();
                $('tr[data-session_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="7">Błąd odświeżania wiersza sesji!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('#sessions').animate({width: '1300px'}, 500);
        showCreateRow();
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sesja/create",
        data: { version: "forIndex" },
        success: function(result) { $('#sessions').append(result); },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem dodawania sesji.</td></tr>';
            $('#sessions tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania sesji
    $('#sessions').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('#sessions').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie sesji w bazie danych
    var year = $('#createRow input[name="year"]').val();
    var type = $('#createRow select[name="type"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sesja",
        data: { year: year, type: type },
        success: function(id) {  refreshRow(id, "add");  },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd dodawania sesji maturalnej!</td></tr>';
            $('#sessions tr.create').before(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania sesji
    $('#sessions').delegate('button.edit', 'click', function() {
        var id = $(this).data('session_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sesja/"+id+"/edit",
            data: { id: id },
            success: function(result) {
                $('tr[data-session_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem modyfikowania sesji.</td></tr>';
                $('tr[data-session_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania sesji
    $('.cancelUpdate').click(function() {
        var id = $(this).data('session_id');
        $('.editRow[data-session_id='+id+']').remove();
        $('tr[data-session_id='+id+']').show();
    });

    $('.update').click(function(){
        update( $(this).data('session_id') );
    });
}

function update(id) {   // zapisanie sesji w bazie danych
    var year = $('tr[data-session_id='+id+'] input[name="year"]').val();
    var type = $('tr[data-session_id='+id+'] select[name="type"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sesja/"+id,
        data: { id: id, year: year, type: type },
        success: function() { refreshRow(id, "edit"); },
        error: function(result) {
            var error = '<tr><td colspan="7" class="error">Błąd modyfikowania sesji!</td></tr>';
            $('tr[data-session_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie sesji (z bazy danych)
    $('#sessions').delegate('.destroy', 'click', function() {
        var id = $(this).data('session_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sesja/" + id,
            success: function() { $('tr[data-session_id='+id+']').remove(); },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd usuwania sesji!</td></tr>';
                $('tr[data-session_id='+id+']').after(error).hide();
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