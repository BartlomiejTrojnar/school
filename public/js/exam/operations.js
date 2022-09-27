// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.09.2022 ------------------------ //
// -------------------- wydarzenia na stronie egzaminów dla opisu egzaminu --------------------- //

// ----------------------------- zarządzanie egzaminami ------------------------------ //
function refreshRow(id, version, type, lp=0) {  // odświeżenie wiersza z egzaminem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin/refreshRow",
        data: { exam_id: id, version: version, lp: lp },
        success: function(result) {
            if(type=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-exam_id='+id+']').remove();
                $('tr[data-exam_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="9">Błąd odświeżania wiersza egzaminu!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        var version = $(this).data('version');
        $(this).hide();
        $('#exams').animate({width: '1500px'}, 500);
        showCreateRow(version);
        return false;
    });
}

function showCreateRow(version) {
    var exam_description_id = 0;
    if(version=="forExamDescription")  exam_description_id = $('input#exam_description_id').val();
    var declaration_id = 0
    if(version=="forDeclaration")  declaration_id = $('input#declaration_id').val();

    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin/create",
        data: { version: version, exam_description_id: exam_description_id, declaration_id: declaration_id },
        success: function(result) {  $('#exams').append(result);  },
        error: function(res) {
            alert(res);
            var error = '<tr><td colspan="9" class="error">Błąd tworzenia wiersza z formularzem dodawania egzaminu.</td></tr>';
            $('#exams tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania egzaminu
    $('#exams').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('table#exams').delegate('#add', 'click', function() {
        var version = $(this).data('version');
        add(version);
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add(version) {   // zapisanie egzaminu w bazie danych
    var declaration_id      = $('#createRow select[name="declaration_id"]').val();
    if(version=="forDeclaration")   declaration_id = $('#createRow input[name="declaration_id"]').val();
    var exam_description_id = $('#createRow select[name="exam_description_id"]').val();
    if(version=="forExamDescription")   exam_description_id = $('#createRow input[name="exam_description_id"]').val();
    var term_id             = $('#createRow select[name="term_id"]').val();
    var points              = $('#createRow input[name="points"]').val();
    var type                = $('#createRow select[name="exam_type"]').val();
    var comments            = $('#createRow input[name="comments"]').val();
    var lp = $('input[name="lp"]').val();
    alert(82);
    return;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin",
        data: { declaration_id: declaration_id, exam_description_id: exam_description_id, term_id: term_id, points: points, type: type, comments: comments },
        success: function(id) {  refreshRow(id, version, 'add', lp);  },
        error: function() {
            var error = '<tr><td colspan="9" class="error">Błąd dodawania egzaminu.</td></tr>';
            $('#exams tr.create').before(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania egzaminu
    $('#exams').delegate('button.edit', 'click', function() {
        var id = $(this).data('exam_id');
        var lp = $(this).parent().parent().children(":first").children().html();
        var version = $(this).data('version');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/egzamin/"+id+"/edit",
            data: { id: id, version: version },
            success: function(result) {
                $('tr[data-exam_id='+id+']').before(result).hide();
                updateClick(lp);
            },
            error: function() {
                var error = '<tr><td colspan="9" class="error">Błąd tworzenia wiersza z formularzem modyfikowania egzaminu.</td></tr>';
                $('tr[data-exam_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania egzaminu
    $('.cancelUpdate').click(function(){
        var id = $(this).data('exam_id');
        $('.editRow[data-exam_id='+id+']').remove();
        $('tr[data-exam_id='+id+']').show();
    });

    $('.update').click(function(){
        var version = $(this).data('version');
        update( $(this).data('exam_id'), version, lp);
    });
}

function update(id, version, lp) {   // zapisanie egzaminu w bazie danych
    var declaration_id      = $('tr[data-exam_id='+id+']  select[name="declaration_id"]').val();
    if(version=="forDeclaration")
        declaration_id = $('tr[data-exam_id='+id+'] input[name="declaration_id"]').val();
    var exam_description_id = $('tr[data-exam_id='+id+']  select[name="exam_description_id"]').val();
    if(version=="forExamDescription")
        exam_description_id = $('tr[data-exam_id='+id+'] input[name="exam_description_id"]').val();
    var term_id             = $('tr[data-exam_id='+id+']  select[name="term_id"]').val();
    var type                = $('tr[data-exam_id='+id+']  select[name="exam_type"]').val();
    var points              = $('tr[data-exam_id='+id+']  input[name="points"]').val();
    var comments            = $('tr[data-exam_id='+id+']  input[name="comments"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin/"+id,
        data: { id: id, declaration_id: declaration_id, exam_description_id: exam_description_id, term_id: term_id, type: type, points: points, comments: comments },
        success: function() {   refreshRow(id, version, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="9" class="error">Błąd modyfikowania egzaminu.</td></tr>';
            $('tr[data-exam_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcieegzaminu (z bazy danych)
    $('#exams').delegate('.destroy', 'click', function() {
        var id = $(this).data('exam_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/egzamin/" + id,
            success: function() {   $('tr[data-exam_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="9" class="error">Błąd usuwania egzaminu.</td></tr>';
                $('tr[data-exam_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}

function showExamsCreateForDeclarationClick() {
    $('#showExamsCreateForDeclaration').click(function(){
        declaration_id = $('input#declaration_id').val();

        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/egzamin/create",
            data: { version: "manyExamsForDeclaration", declaration_id: declaration_id },
            success: function(result) {  
                $('#exams').after(result);
                $('#createManyExams').animate({opacity: '100%'}, 500);
                createManyExamsClick();
            },
            error: function() {
                var error = '<p class="error">Błąd tworzenia formularza dodawania egzaminów.</p>';
                $('#exams').after(error);
            },
        });
        return false;
    });
}

function createManyExamsClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania egzaminu
    $('#createManyExams li').click(function() {
        if( $(this).hasClass('checked') )   $(this).removeClass('checked');
        else $(this).addClass('checked');
    });

    $('#createManyExams button').click(function() {
        var examsDN = [];
        count = 0;
        // zapamiętanie wszystkich zaznaczonych (opisów) egzaminów
        $('#createManyExams li.checked').each(function() {  examsDN[count++] = $(this).data("exam_description_id");  });
        var declaration_id = $('#createManyExams input[name="declaration_id"]').val();
        addExamsForDeclaration(declaration_id, examsDN);
        $.when( $('#createManyExams').animate({opacity: '0%'}, 1500) ).then( function() {
            $('#createManyExams').remove();
        } );
    });
}

function addExamsForDeclaration(declaration_id, examsDN) {   // zapisanie w bazie danych serii egzaminów dla deklaracji
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/exam/addExamsForDeclaration",
        data: { declaration_id: declaration_id, examsDN: examsDN },
        success: function() { location.reload();  },
        error: function() { alert('Błąd: exam/operations.js - funkcja addExamsForDeclaration'); }
	});
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();

    showExamsCreateForDeclarationClick();
});