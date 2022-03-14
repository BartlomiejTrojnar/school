// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.11.2021 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania klas ucznia ----------------------- //

var colspan=8;

function sessionChanged() {  // wybór sesji maturalnej w polu select
    $('select[name="session_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sesja/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

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

// ------------------------------ zarządzanie deklaracjami sesji ------------------------------- //
function refreshRow(id, version, type, lp=0) {  // odświeżenie wiersza z opisem egzaminem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/declaration/refreshRow",
        data: { id: id, version: version, lp: lp },
        success: function(result) {
            if(type=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-declaration_id='+id+']').remove();
                $('tr[data-declaration_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            if(version=="forIndex" || version=="forGrade") colspan=9;
            var error = '<tr><td class="error" colspan="'+colspan+'">Błąd odświeżania wiersza deklaracji!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

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

function editClick() {     // kliknięcie przycisku modyfikowania deklaracji
    $('#declarations').delegate('button.edit', 'click', function() {
        var id = $(this).data('declaration_id');
        var lp = $(this).parent().parent().children(":first").children().html();
        var version = $(this).data('version');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/deklaracja/"+id+"/edit",
            data: { id: id, version: version },
            success: function(result) {
                $('tr[data-declaration_id='+id+']').before(result).hide();
                updateClick(lp);
            },
            error: function() {
                if(version=="forIndex" || version=="forGrade") colspan=9;
                var error = '<tr><td colspan="'+colspan+'" class="error">Błąd tworzenia wiersza z formularzem modyfikowania deklaracji.</td></tr>';
                $('tr[data-declaration_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania deklaracji
    $('.cancelUpdate').click(function() {
        var id = $(this).data('declaration_id');
        $('.editRow[data-declaration_id='+id+']').remove();
        $('tr[data-declaration_id='+id+']').show();
    });

    $('.update').click(function(){
        var version = $(this).data('version');
        update( $(this).attr('data-declaration_id'), version, lp );
    });
}

function update(id, version, lp) {   // zapisanie deklaracji w bazie danych
    var student_id          = $('tr[data-declaration_id='+id+'] select[name="student_id"]').val();
    if(version=="forStudent")
        student_id = $('tr[data-declaration_id='+id+'] input[name="student_id"]').val();
    var session_id = $('tr[data-declaration_id='+id+'] select[name="session_id"]').val();
    if(version=="forSession")
        session_id = $('tr[data-declaration_id='+id+'] input[name="session_id"]').val();
    var application_number  = $('tr[data-declaration_id='+id+'] input[name="application_number"]').val();
    var student_code        = $('tr[data-declaration_id='+id+'] input[name="student_code"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/deklaracja/"+id,
        data: { id: id, student_id: student_id, session_id: session_id, application_number: application_number, student_code: student_code },
        success: function() { refreshRow(id, version, "edit", lp); },
        error: function() {
            if(version=="forIndex" || version=="forGrade") colspan=9;
            var error = '<tr><td colspan="'+colspan+'" class="error">Błąd modyfikowania deklaracji.</td></tr>';
            $('tr[data-declaration_id='+id+'].editRow').after(error).hide();
        },
    });
}

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

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    sessionChanged();
    gradeChanged();
    studentChanged();

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});