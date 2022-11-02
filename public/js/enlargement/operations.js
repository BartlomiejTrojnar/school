// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.11.2021 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania klas ucznia ----------------------- //

/*
function gradeChanged() {  // wybór klasy w polu select
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/klasa/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

function studentChanged() {  // wybór ucznia w polu select
    $('select[name="student_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/uczen/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}
*/

// ------------------------------ zarządzanie deklaracjami sesji ------------------------------- //
function refreshRow(id, version, operation) {  // odświeżenie wiersza z rozszerzeniem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/enlargement/refreshRow",
        data: { id: id, version: version },
        success: function(result) {
            if(operation=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $.when( $('.editRow[data-enlargement_id='+id+']').fadeOut(500) ).then(function() {
                    $('.editRow[data-enlargement_id='+id+']').remove();
                    $('[data-enlargement_id='+id+']').replaceWith(result);
                    $('[data-enlargement_id='+id+']').hide().fadeIn(1000);
                });
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="6">Błąd odświeżania wiersza rozszerzenia!</td></tr>';
            if(operation=="add") {
                $('tr.create').before(error);
            }
            else {
                $('.editRow[data-enlargement_id='+id+']').before(error);
                $('.error').hide().fadeIn(750);
                $.when( $('.editRow[data-enlargement_id='+id+']').fadeOut(500) ).then(function() {
                    $('.editRow[data-enlargement_id='+id+']').remove();
                });
            }
        },
    });
}
/*
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        var version = $(this).data('version');
        $(this).hide();
        $('#declarations').animate({width: '1100px'}, 500);
        showCreateRow(version);
        return false;
    });
}

function showCreateRow(version) {
    var session_id=0;
    if(version=="forSession")  session_id = $('input#session_id').val();
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/deklaracja/create",
        data: { session_id: session_id, version: version },
        success: function(result) {
            $('#declarations').append(result);
            if(version=="forStudent") $('#declarationsForStudent footer').append(result);
        },
        error: function() {
            if(version=="forIndex" || version=="forGrade") colspan=9;
            var error = '<tr><td colspan="'+colspan+'" class="error">Błąd tworzenia wiersza z formularzem dodawania deklaracji.</td></tr>';
            $('#declarations tr.create').after(error);
            if(version=="forStudent") $('#declarationsForStudent footer').append('<p class="error">Błąd tworzenia formularza dodawania deklaracji.</p>');
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania deklaracji
    $('#declarations').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('#declarations').delegate('#add', 'click', function() {
        var version = $(this).data('version');
        add(version);
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add(version) {   // zapisanie deklaracji w bazie danych
    var student_id          = $('#createRow select[name="student_id"]').val();
    if(version=="forStudent")   student_id = $('#createRow input[name="student_id"]').val();
    var session_id          = $('#createRow select[name="session_id"]').val();
    if(version=="forSession")   session_id = $('#createRow input[name="session_id"]').val();
    var application_number  = $('#createRow input[name="application_number"]').val();
    var student_code        = $('#createRow input[name="student_code"]').val();
    var lp = $('input[name="lp"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/deklaracja",
        data: { student_id: student_id, session_id: session_id, application_number: application_number, student_code: student_code },
        success: function(id) {  refreshRow(id, version, 'add', lp);  },
        error: function() {
            if(version=="forIndex" || version=="gorGrade") colspan=9;
            var error = '<tr><td colspan="'+colspan+'" class="error">Błąd dodawania deklaracji dla ucznia</p></td></tr>';
            $('#declarations tr.create').after(error);
        },
    });
}
*/
function editClick() {     // kliknięcie przycisku modyfikowania rozszerzenia
    $('#enlargements').delegate('button.edit', 'click', function() {
        var id = $(this).data('enlargement_id');
        var version = $(this).data('version');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rozszerzenie/"+id+"/edit",
            data: { id: id, version: version },
            success: function(result) {
                $.when(  $('tr[data-enlargement_id='+id+']').hide(750)  ).then(function() {
                    $('tr[data-enlargement_id='+id+']').before(result);
                    $('tr[data-enlargement_id='+id+'].editRow').hide().show(1500);
                    updateClick();
                });
            },
            error: function() {
                var error = '<tr><td colspan="6" class="error">Błąd tworzenia wiersza z formularzem modyfikowania rozszerzenia.</td></tr>';
                $('tr[data-enlargement_id='+id+']').before(error);
                $('td.error').hide().show(375);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania rozszerzenia
    $('.cancelUpdate').click(function() {
        var id = $(this).data('enlargement_id');
        $.when( $('.editRow[data-enlargement_id='+id+']').hide(500) ).then(function() {
            $('.editRow[data-enlargement_id='+id+']').remove();
            $('tr[data-enlargement_id='+id+']').fadeOut(1).fadeIn(1000);
        });        
    });

    $('.update').click(function(){
        update( $(this).attr('data-enlargement_id'), $(this).data('version') );
    });
}

function update(id, version) {   // zapisanie rozszerzenia w bazie danych
    var student_id  = $('tr[data-enlargement_id='+id+'] select[name="student_id"]').val();
    var subject_id  = $('tr[data-enlargement_id='+id+'] select[name="subject_id"]').val();
    var level       = $('tr[data-enlargement_id='+id+'] input[name="level"]').val();
    var choice      = $('tr[data-enlargement_id='+id+'] input[name="choice"]').val();
    var resignation = $('tr[data-enlargement_id='+id+'] input[name="resignation"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rozszerzenie/"+id,
        data: { id: id, student_id: student_id, subject_id: subject_id, level: level, choice: choice, resignation: resignation },
        success: function(wynik) {
            refreshRow(id, version, "edit");
        },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Błąd modyfikowania rozszerznia.</td></tr>';
            $.when( $('.editRow[data-enlargement_id='+id+']').hide(500) ).then(function() {
                $('.editRow[data-enlargement_id='+id+']').after(error).remove();
                $('td.error').hide().fadeIn(750);
                $('[data-enlargement_id='+id+']').fadeIn(1500);
            });
        },
    });
}
/*
function destroyClick() {  // usunięcie deklaracji (z bazy danych)
    $('#declarations').delegate('.destroy', 'click', function() {
        var id = $(this).data('declaration_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/deklaracja/" + id,
            success: function() {  $('tr[data-declaration_id='+id+']').remove();  },
            error: function() {
                if(version=="forIndex" || version=="forGrade") colspan=9;
                var error = '<tr><td colspan="'+colspan+'" class="error">Błąd usuwania deklaracji.</td></tr>';
                $('tr[data-declaration_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}
*/

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    //gradeChanged();
    //studentChanged();

    //showCreateRowClick();
    //addClick();
    editClick();
    //destroyClick();
});