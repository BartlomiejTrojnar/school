// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 15.10.2021 ------------------------ //
// ------------- wydarzenia na stronie wyświetlania wyboru podręczników ------------------------ //

function schoolChanged() {  // wybór szkoły w polu select
    $('select[name="school_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/szkola/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}

function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="school_year_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}

function studyYearChanged() {  // wybór roku nauki w polu select
    $('select[name="studyYear"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/studyYear/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
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

function levelChanged() {  // wybór poziomu w polu select
    $('select[name="level"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/level/change/" + $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}

function teacherChanged() {  // wybór nauczyciela w polu select
    $('select[name="teacher_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/nauczyciel/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}

// ----------------------------- zarządzanie wyborami podręcznika ------------------------------ //
function refreshRow(id, version, lp=0) {  // odświeżenie wiersza z klasą o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/textbookChoice/refreshRow",
        data: { id: id, version: "forIndex", lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-textbookchoice_id='+id+']').remove();
                $('tr[data-textbookchoice_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            if(version=="add"){
                var error = '<tr><td class="error" colspan="7">Błąd odświeżania wiersza z wyborem podręcznika.</td></tr>';
                $('tr.create').before(error);
            }
            else {
                var error = '<tr><td class="error" colspan="7">Błąd odświeżania wiersza z wyborem podręcznika.</td></tr>';
                $('tr.editRow[data-textbookchoice_id='+id+']').remove();
                $('tr[data-textbookchoice_id='+id+']').after(error).show();
            }
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('#textbookChoicesTable').animate({'width': '1500px'}, 500);
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/wybor_podrecznika/create",
        data: { version: "forIndex" },
        success: function(result) {  $('#textbookChoicesTable').append(result);  },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem dodawania wyboru podręcznika.</td></tr>';
            $('#textbookChoicesTable tbody tr:last-child').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania wyboru podręcznika
    $('#textbookChoicesTable').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });

    $('#textbookChoicesTable').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });
}

function add() {   // zapisanie wyboru podręcznika w bazie danych
    var textbook_id     = $('#createRow select[name="textbook_id"]').val();
    var school_id       = $('#createRow select[name="school_id"]').val();
    var school_year_id  = $('#createRow select[name="school_year_id"]').val();
    var learning_year   = $('#createRow input[name="learning_year"]').val();
    var level           = $('#createRow select[name="level"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/wybor_podrecznika",
        data: { textbook_id: textbook_id, school_id: school_id, school_year_id: school_year_id, learning_year: learning_year, level: level },
        success: function(id) {  refreshRow(id, "add", 999);  },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd dodawania wyboru dla podręcznika</td></tr>';
            $('#textbookChoicesTable tr.create').before(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania wyboru podręcznika
    $('#textbookChoicesTable').delegate('button.edit', 'click', function() {
        var id = $(this).data('textbookchoice_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/wybor_podrecznika/"+id+"/edit",
            data: { id: id, version: "forIndex" },
            success: function(result) {
                $('tr[data-textbookchoice_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem modyfikowania wyboru podręcznika.</td></tr>';
                $('tr[data-textbookchoice_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania wyboru podręcznika
    $('.cancelUpdate').click(function() {
        var id = $(this).data('textbookchoice_id');
        $('.editRow[data-textbookchoice_id='+id+']').remove();
        $('tr[data-textbookchoice_id='+id+']').show();
    });

    $('.update').click(function(){
        update( $(this).data('textbookchoice_id') );
    });
}

function update(id) {   // zapisanie wyboru podręcznika w bazie danych
    var textbook_id     = $('tr.editRow[data-textbookchoice_id='+id+'] input[name="textbook_id"]').val();
    var school_id       = $('tr.editRow[data-textbookchoice_id='+id+'] select[name="school_id"]').val();
    var school_year_id  = $('tr.editRow[data-textbookchoice_id='+id+'] select[name="school_year_id"]').val();
    var learning_year   = $('tr.editRow[data-textbookchoice_id='+id+'] input[name="learning_year"]').val();
    var level           = $('tr.editRow[data-textbookchoice_id='+id+'] select[name="level"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/wybor_podrecznika/"+id,
        data: { id: id, textbook_id: textbook_id, school_id: school_id, school_year_id: school_year_id, learning_year: learning_year, level: level },
        success: function() {
            var lp = $('tr[data-textbookchoice_id='+id+']').data('lp');
            refreshRow(id, "edit", lp);
        },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd modyfikowania wyboru podręcznika.</td></tr>';
            $('tr.editRow[data-textbookchoice_id='+id+']').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie wyboru podręcznika (z bazy danych)
    $('#textbookChoicesTable').delegate('.destroy', 'click', function() {
        var id = $(this).data('textbookchoice_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/wybor_podrecznika/" + id,
            success: function() {  $('tr[data-textbookchoice_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd usuwania wyboru podręcznika.</td></tr>';
                $('tr[data-textbookchoice_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}

function extensionClick() {  // usunięcie wyboru podręcznika (z bazy danych)
    $('#textbookChoicesTable').delegate('.extension', 'click', function() {
        var id = $(this).data('textbookchoice_id');
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/textbookChoice/extension/" + id,
            success: function() {  $('button.extension[data-textbookchoice_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd przedłużania wyboru podręcznika.</td></tr>';
                $('tr[data-textbookchoice_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
    verifyExtensions();
}

function verifyExtensions() {
    var id;
    $('.extension').each(function(){
        id = $(this).data('textbookchoice_id');
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/textbookChoice/verifyExtension/" + id,
            success: function(result) {  $('button.extension[data-textbookchoice_id='+result+']').remove();  },
            error: function(result) {  alert(result+' = błąd w skrypcie textbookChoicesIndex w funkcji verifyExtansions');  }
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {

    schoolChanged();
    schoolYearChanged();
    studyYearChanged();
    subjectChanged();
    levelChanged();
    teacherChanged();

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
    extensionClick();
});