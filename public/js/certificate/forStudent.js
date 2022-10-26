// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 25.10.2022 ------------------------ //
// ------------------- wydarzenia na stronie wyświetlania świadectw ucznia --------------------- //
/*
// ------------------------------ zarządzanie deklaracjami ucznia ------------------------------ //
function refreshDeclaration(id, add=0) {  // odświeżenie kontenera z deklaracją
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/declaration/refreshForStudent",
        data: { id: id },
        success: function(result) {
            if(add){
                $('section#declarations footer').before(result);
                $('.declaration[data-declaration_id="'+id+'"] header').hide().show(500);
            }
            else {
                $('.declaration[data-declaration_id="'+id+'"]').replaceWith(result);
                $('.declaration[data-declaration_id="'+id+'"] header').hide().show(500);
            }   
        },
        error: function() {
            var error = '<p class="error">Błąd! Nie mogę załadować deklaracji.</p>';
            $('.declaration[data-declaration_id="'+id+'"]').append(error);
        },
    });
}
*/
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/certificate/create",
        data: { version: "forStudent" },
        success: function(result) {
            $('#certificatesTable .footer').before(result);
            $('#createRow').hide();
            $.when( $('#showCreateRow').hide(500) ).then(  function() {
                $('#createRow').show(500);
            });
        },
        error: function() {
            var error = '<tr><td class="error" colspan="6">Błąd tworzenia wiersza z formularzem dodawania świadectwa.</td></tr>';
            $('#certificatesTable .footer').before(error);
            $('#certificatesTable .error').hide().show(500);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania świadectwa
    $('#certificatesTable').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(500) ).then(  function() {
            $('#showCreateRow').show(500);
            $('#createRow').remove();
         });
    });

    $('#certificatesTable').delegate('#add', 'click', function() {
        add();
        $.when( $('#createRow').hide(500) ).then(  function() {
           $('#showCreateRow').show(500);
           $('#createRow').remove();
        });
    });
}

function add() {   // zapisanie świadectwa w bazie danych
    var student_id      = $('#createRow input[name="student_id"]').val();
    var type            = $('#createRow select[name="type"]').val();
    var templates_id    = $('#createRow select[name="templates_id"]').val();
    var council_date    = $('#createRow input[name="council_date"]').val();
    var date_of_issue   = $('#createRow input[name="date_of_issue"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/certificate",
        data: { student_id: student_id, type: type, templates_id: templates_id, council_date: council_date, date_of_issue: date_of_issue },
        success: function(id) {  
            alert(id);
            refreshCertificate(id, 1);
        },
        error: function() {
            var error = '<tr><td class="error" colspan="6">Błąd tworzenia wiersza z formularzem dodawania świadectwa.</td></tr>';
            $('#certificatesTable .footer').before(error);
            $('#certificatesTable .error').hide().show(1000);
        },
    });
}
/*
function editClick() {     // kliknięcie przycisku modyfikowania deklaracji
    $('#declarations').delegate('button.edit', 'click', function() {
        var id = $(this).data('declaration_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/deklaracja/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) {
                $.when( $('.declaration[data-declaration_id="'+id+'"] header').hide(500) ).then(  function() {
                    $('.declaration[data-declaration_id="'+id+'"] header').replaceWith(result);
                    $('.declaration[data-declaration_id="'+id+'"] header').show(700);
                    updateClick();
                });
            },
            error: function() {
                var error = '<p class="error">Błąd tworzenia wiersza z formularzem dodawania deklaracji.</p>';
                $('.declaration[data-declaration_id="'+id+'"]').append(error);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania deklaracji
    $('.cancelUpdate').click(function() {
        var id = $(this).attr('data-declaration_id')
        $.when( $('header.editTable[data-declaration_id='+id+']').hide(500) ).then(  function() {
            refreshDeclaration( $(this).data('declaration_id') );
        });
    });

    $('.update').click(function(){
        var id = $(this).attr('data-declaration_id');
        $.when( $('header.editTable[data-declaration_id='+id+']').hide(500) ).then(  function() {
            update(id);
        });
    });
}

function update(id) {   // zapisanie deklaracji w bazie danych
    var student_id          = $('header[data-declaration_id='+id+'] input[name="student_id"]').val();
    var session_id          = $('header[data-declaration_id='+id+'] select[name="session_id"]').val();
    var application_number  = $('header[data-declaration_id='+id+'] input[name="application_number"]').val();
    var student_code        = $('header[data-declaration_id='+id+'] input[name="student_code"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/deklaracja/"+id,
        data: { id: id, student_id: student_id, session_id: session_id, application_number: application_number, student_code: student_code },
        success: function() {  refreshDeclaration(id);  },
        error: function() {
            var error = '<p class="error">Błąd! Nie można zapisać zmian.</p>';
            $('.declaration[data-declaration_id="'+id+'"]').append(error);
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
            success: function() {  $('.declaration[data-declaration_id="'+id+'"]').hide(500);  },
            error: function() {
                var error = '<p class="error">Błąd! Nie można usunąć deklaracji.</p>';
                $('.declaration[data-declaration_id="'+id+'"]').append(error);
            }
        });
        return false;
    });
}


// ------------------------------ zarządzanie egzminami w deklaracjach ucznia ------------------------------ //
function refreshExamRow(id, exam_id, add) {  // odświeżenie wiersza z egzaminem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin/refreshRow",
        data: { exam_id: exam_id, version: "forStudentDeclaration" },
        success: function(result) {
            if(add){
                $('div.declaration[data-declaration_id="'+id+'"] tr.examCreate').before(result);
                $('tr[data-exam_id='+exam_id+']').hide();
                $('tr[data-exam_id='+exam_id+']').show(500);
                $('div.declaration[data-declaration_id="'+id+'"] tr.examCreate *').show(500);
            }
            else {
                $('tr[data-exam_id='+exam_id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="7">Błąd odświeżania wiersza egzaminu!</td></tr>';
            $('div.declaration[data-declaration_id="'+id+'"] tr.examCreate').before(error);
        },
    });
}

function showExamCreateRowClick() {
    $('#declarations').delegate('.showExamCreateRow', 'click', function() {
        $(this).hide(500);
        var declaration_id = $(this).data('declaration_id');
        showExamCreateRow(declaration_id);
        return false;
    });
}

function showExamCreateRow(declaration_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin/create",
        data: { version: "forStudentDeclaration", declaration_id: declaration_id },
        success: function(result) { $('div.declaration[data-declaration_id="'+declaration_id+'"] section.exams table').append(result);  },
        error: function() {
            var error = '<tr><td class="error" colspan="7">Błąd tworzenia wiersza z formularzem dodawania egzaminu.</td></tr>';
            $('div.declaration[data-declaration_id="'+declaration_id+'"] section.exams tr.examCreate').before(error);
        },
    });
}

function examAddClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania deklaracji
    $('#declarations').delegate('.exams .cancelAdd', 'click', function() {
        var id = $(this).parent().parent().data('declaration_id');
        $.when( $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow').hide(500) ).then(  function() {
            $('div.declaration[data-declaration_id="'+id+'"] tr.examCreateRow').hide(500).remove();
            $('div.declaration[data-declaration_id="'+id+'"] tr.examCreate *').show(500);
        });
    });

    $('#declarations').delegate('.exams .add', 'click', function() {
        var id = $(this).parent().parent().data('declaration_id');
        addExam(id);
        $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow').hide(400);
    });
}

function addExam(id) {   // zapisanie deklaracji w bazie danych
    var declaration_id      = $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow input[name="declaration_id"]').val();
    var exam_description_id = $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow select[name="exam_description_id"]').val();
    var term_id             = $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow select[name="term_id"]').val();
    var points              = $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow input[name="points"]').val();
    var type                = $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow select[name="exam_type"]').val();
    var comments            = $('div.declaration[data-declaration_id="'+id+'"] .examCreateRow input[name="comments"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin",
        data: { declaration_id: declaration_id, exam_description_id: exam_description_id, term_id: term_id, points: points, type: type, comments: comments },
        success: function(exam_id) {  refreshExamRow(id, exam_id, true);  },
        error: function() {
            var error = '<tr><td class="error" colspan="7">Nie można dodać egzaminu.</td></tr>';
            $('div.declaration[data-declaration_id="'+id+'"] .exams table').append(error);
        },
    });
}

function examEditClick() {     // kliknięcie przycisku modyfikowania egzaminu
    $('#declarations').delegate('section.exams .editExam', 'click', function() {
        var id = $(this).data('exam_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/egzamin/"+id+"/edit",
            data: { id: id, version: "forStudentDeclaration" },
            success: function(result) {  $('tr[data-exam_id='+id+']').hide(500).after(result).hide();  },
            error: function() {
                var error = '<tr><td class="error" colspan="7">Nie można utworzyć formularza do zmiany danych egzaminu.</td></tr>';
                $('tr[data-exam_id='+id+']').after(error);
            },
        });
    });
}

function examUpdateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania egzaminu
    $('#declarations').delegate('.examCancelUpdate', 'click', function() {
        var id = $(this).data('exam_id');
        $('.editRow[data-exam_id='+id+']').remove();
        $('tr[data-exam_id='+id+']').show();
    });

    $('#declarations').delegate('.examUpdate', 'click', function() {
        var id = $(this).data('exam_id');
        $.when( $('.editRow[data-exam_id='+id+']').hide(500) ).then(  function() {
            examUpdate( id );
            $('.editRow[data-exam_id='+id+']').remove();
        });
    });
}

function examUpdate(id) {   // zapisanie egzaminu w bazie danych
    var declaration_id      = $('tr[data-exam_id='+id+']  input[name="declaration_id"]').val();
    var exam_description_id = $('tr[data-exam_id='+id+']  select[name="exam_description_id"]').val();
    var term_id             = $('tr[data-exam_id='+id+']  select[name="term_id"]').val();
    var type                = $('tr[data-exam_id='+id+']  select[name="exam_type"]').val();
    var points              = $('tr[data-exam_id='+id+']  input[name="points"]').val();
    var comments            = $('tr[data-exam_id='+id+']  input[name="comments"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/egzamin/"+id,
        data: { id: id, declaration_id: declaration_id, exam_description_id: exam_description_id, term_id: term_id, type: type, points: points, comments: comments },
        success: function() {   refreshExamRow(declaration_id, id);  },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd modyfikowania egzaminu.</td></tr>';
            $('tr[data-exam_id='+id+'].editRow').after(error).hide();
        },
    });
}

function examDestroyClick() {  // usunięcie egzaminu (z bazy danych)
    $('#declarations').delegate('.exams .destroyExam', 'click', function() {
        var id = $(this).data('exam_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/egzamin/" + id,
            success: function() {   $('tr[data-exam_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd usuwania egzaminu.</td></tr>';
                $('tr[data-exam_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}


function minusClick() {     // ukrycie egzaminów dla deklaracji
    $('#declarations').delegate('.fa-minus', 'click', function() {
        var id = $(this).parent().parent().data('declaration_id');
        $('div.declaration[data-declaration_id="'+id+'"] section.exams').hide(1000);
        $(this).replaceWith('<i class="fa fa-plus" style="float: left; margin: 18px;"></i>');
    });
}

function plusClick() {     // pokazanie egzaminów dla deklaracji
    $('#declarations').delegate('.fa-plus', 'click', function() {
        var id = $(this).parent().parent().data('declaration_id');
        $('div.declaration[data-declaration_id="'+id+'"] section.exams').show(1000);
        $(this).replaceWith('<i class="fa fa-minus" style="float: left; margin: 18px;"></i>');
    });
}
*/
// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowClick();
    addClick();
    //editClick();
    //destroyClick();

    //showExamCreateRowClick();
    //examAddClick();
    //examEditClick();
    //examUpdateClick();
    //examDestroyClick();

    //minusClick();
    //plusClick();
});