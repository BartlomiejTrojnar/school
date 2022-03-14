// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.10.2021 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania deklaracji ----------------------- //

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

function subjectChanged() {  // wybór przedmiotu w polu select
    $('select[name="subject_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

function examTypeChanged() {  // wybór typu egzaminu w polu select
    $('select[name="exam_type"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/type/change/" + $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

function levelChanged() {  // wybór poziomu egzaminu w polu select
    $('select[name="level"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/level/change/" + $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}


// ----------------------------- zarządzanie opisami egzaminu ------------------------------ //
function refreshRow(id, version, lp=0) {  // odświeżenie wiersza z opisem egzaminem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/examDescription/refreshRow",
        data: { id: id, version: "forIndex", lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-exam_description_id='+id+']').remove();
                $('tr[data-exam_description_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="9">Błąd odświeżania wiersza z opisem egzaminu!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('#examDescriptions').animate({width: '1500px'}, 500);
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/opis_egzaminu/create",
        data: { version: "forIndex" },
        success: function(result) { $('#examDescriptions').append(result); },
        error: function() {
            var error = '<tr><td colspan="9" class="error">Błąd tworzenia wiersza z formularzem dodawania opisu egzaminu.</td></tr>';
            $('#examDescriptions tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania opisu egzaminu
    $('#examDescriptions').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('#examDescriptions').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie opisu egzaminu w bazie danych
    var session_id  = $('#createRow select[name="session_id"]').val();
    var subject_id  = $('#createRow select[name="subject_id"]').val();
    var exam_type   = $('#createRow select[name="exam_type"]').val();
    var level       = $('#createRow select[name="level"]').val();
    var max_points  = $('#createRow input[name="max_points"]').val();
    var lp = $('input[name="lp"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/opis_egzaminu",
        data: { session_id: session_id, subject_id: subject_id, exam_type: exam_type, level: level, max_points: max_points },
        success: function(id) {  refreshRow(id, 'add', lp);   },
        error: function() {
            var error = '<tr><td colspan="9" class="error">Błąd dodawania opisu egzaminu.</td></tr>';
            $('#examDescriptions tr.create').before(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania opisu egzaminu
    $('#examDescriptions').delegate('button.edit', 'click', function() {
        var id = $(this).data('exam_description_id');
        var lp = $(this).parent().parent().children(":first").children().html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/opis_egzaminu/"+id+"/edit",
            data: { id: id, version: "forIndex" },
            success: function(result) {
                $('tr[data-exam_description_id='+id+']').before(result).hide();
                updateClick(lp);
            },
            error: function() {
                var error = '<tr><td colspan="9" class="error">Błąd tworzenia wiersza z formularzem modyfikowania opisu egzaminu.</td></tr>';
                $('tr[data-exam_description_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania opisu egzaminu
    $('.cancelUpdate').click(function(){
        var id = $(this).data('exam_description_id');
        $('.editRow[data-exam_description_id='+id+']').remove();
        $('tr[data-exam_description_id='+id+']').show();
    });

    $('.update').click(function(){
        update( $(this).data('exam_description_id'), lp);
    });
}

function update(id, lp) {   // zapisanie opisu egzaminu w bazie danych
    var session_id  = $('tr[data-exam_description_id='+id+'] select[name="session_id"]').val();
    var subject_id  = $('tr[data-exam_description_id='+id+'] select[name="subject_id"]').val();
    var exam_type   = $('tr[data-exam_description_id='+id+'] select[name="exam_type"]').val();
    var level       = $('tr[data-exam_description_id='+id+'] select[name="level"]').val();
    var max_points  = $('tr[data-exam_description_id='+id+'] input[name="max_points"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/opis_egzaminu/"+id,
        data: { id: id, session_id: session_id, subject_id: subject_id, exam_type: exam_type, level: level, max_points: max_points },
        success: function() {  refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="9" class="error">Błąd modyfikowania opisu egzaminu.</td></tr>';
            $('tr[data-exam_description_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie opisu egzaminu (z bazy danych)
    $('#examDescriptions').delegate('.destroy', 'click', function() {
        var id = $(this).data('exam_description_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/opis_egzaminu/" + id,
            success: function() { $('tr[data-exam_description_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="9" class="error">Błąd usuwania opisu egzaminu.</td></tr>';
                $('tr[data-exam_description_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    sessionChanged();
    subjectChanged();
    examTypeChanged();
    levelChanged();

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});