// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) kwiecień 2021 -------------------- //
// ---------------------- wydarzenia na stronie wyświetlania klas ucznia ----------------------- //

// ------------------------------ zarządzanie deklaracjami ucznia ------------------------------ //
function refreshDeclarationsTable(student_id) {  // odświeżenie tabeli z deklaracjiami dla ucznia
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/deklaracja/refreshTable",
        data: { student_id: student_id, version: "forStudent" },
        success: function(result) {
            $('section#declarationsTable').replaceWith(result);
            showCreateRowClick();
            addClick();
            editClick();
            destroyClick();
        },
        error: function() {
            var error = '<p class="error">Błąd odświeżania tabeli z deklaracjami dla ucznia.</p>';
            $('table#declarations').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#declarations').animate({width: '1100px'}, 500);
        var student_id = $('input#student_id').val();
        showCreateRow(student_id);
        return false;
    });
}

function showCreateRow(student_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/deklaracja/create",
        data: { student_id: student_id, version: "forStudent" },
        success: function(result) { $('table#declarations').append(result); },
        error: function() {
            var error = '<tr><td colspan="8"><p class="error">Błąd tworzenia wiersza z formularzem dodawania deklaracji.</p></td></tr>';
            $('table#declarations tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania deklaracji
    $('table#declarations').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });

    $('table#declarations').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });
}

function add() {   // zapisanie deklaracji w bazie danych
    var student_id = $('#createRow input[name="student_id"]').val();
    var session_id = $('#createRow select[name="session_id"]').val();
    var application_number = $('#createRow input[name="application_number"]').val();
    var student_code = $('#createRow input[name="student_code"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/deklaracja",
        data: { student_id: student_id, session_id: session_id, application_number: application_number, student_code: student_code },
        success: function() {  refreshDeclarationsTable(student_id);  },
        error: function() {
            var error = '<tr><td colspan="8"><p class="error">Błąd dodawania deklaracji dla ucznia</p></td></tr>';
            $('table#declarations tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania egzaminu
    $('button.edit').click(function() {
        var id = $(this).attr('data-declarationId');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/deklaracja/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) {
                $('tr[data-declarationId='+id+']').before(result).hide();
                updateClick();
            },
            error: function(result) {
                var error = '<tr><td colspan="8"><p class="error">Błąd tworzenia wiersza z formularzem modyfikowania deklaracji.</p></td></tr>';
                $('tr[data-declarationId='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania deklaracji
    $('.cancelUpdate').click(function() {
        var id = $(this).attr('data-declarationId');
        $('.editRow[data-declarationId='+id+']').remove();
        $('tr[data-declarationId='+id+']').show();
        return false;
    });

    $('.update').click(function(){
        var id = $(this).attr('data-declarationId');
        update(id);
        return false;
    });
}

function update(id) {   // zapisanie deklaracji w bazie danych
    var student_id = $('tr[data-declarationId='+id+'] input[name="student_id"]').val();
    var session_id = $('tr[data-declarationId='+id+'] select[name="session_id"]').val();
    var application_number = $('tr[data-declarationId='+id+'] input[name="application_number"]').val();
    var student_code = $('tr[data-declarationId='+id+'] input[name="student_code"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/deklaracja/"+id,
        data: { id: id, student_id: student_id, session_id: session_id, application_number: application_number, student_code: student_code },
        success: function() { refreshDeclarationsTable(student_id); },
        error: function() {
            var error = '<tr><td colspan="8"><p class="error">Błąd modyfikowania deklaracji.</p></td></tr>';
            $('tr[data-declarationId='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie deklaracji (z bazy danych)
    $('.destroy').click(function() {
        var id = $(this).attr('data-declarationId');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/deklaracja/" + id,
            success: function() {
                var student_id = $('input#student_id').val();
                refreshDeclarationsTable(student_id);
            },
            error: function() {
                var error = '<tr><td colspan="8"><p class="error">Błąd usuwania deklaracji.</p></td></tr>';
                $('tr[data-declarationId='+id+']').after(error).hide();
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