// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 01.09.2021 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania klas uczniów ---------------------- //

function gradeButtonClick() {
    $('#gradeButtons button').click(function() {
        var studyYear = $(this).attr('data-study-year');
        var schoolYear = $(this).attr('data-school-year');
        $('#schoolYear').val(parseInt(schoolYear)+1900);
        if(studyYear) {
            var year = $(this).attr('data-year');
            $('input#dateStart').val( year-1+'-09-01' );
            $('input#dateEnd').val( year+'-08-31' );
        }
        else {
            $('input#dateStart').val( '' );
            $('input#dateEnd').val( '' );
        }
        verifyAndDisplayStudents( $('input#dateStart').val(), $('input#dateEnd').val() );
        if(studyYear<=1) $('#createFromPreviousYear').hide();
        else $('#createFromPreviousYear').show();
        return false;
    });
}

function dateStartOrEndChanged() {
    $('input#dateStart').bind('blur', function(){
        verifyAndDisplayStudents( $(this).val(), $('#dateEnd').val() );
        return false;
    });
    $('input#dateEnd').bind('blur', function(){
        verifyAndDisplayStudents( $('#dateStart').val(), $(this).val() );
        return false;
    });
}

function verifyAndDisplayStudents(start, end) {
    var lp = 0;
    $('section#studentGrades tr').each( function() {
        if( $(this).attr('class')=="create" ) return true;
        if( (end && $(this).data('start') > end) || (start && $(this).data('end') < start) ) {
            $(this).css( 'background', 'red' );
            $(this).fadeOut( 1000 );
        }
        else {
            $(this).fadeIn( 1000 );
            $(this).css( 'background', 'transparent' );
            $('td:first-child', this).html(lp++);
        }
    });
}

function editAllClick() {
    $('#editAll').bind('click', function() {
        var dateStart = $('input#dateStart').val();
        var dateEnd = $('input#dateEnd').val();
        var href = $(this).data('href');
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: href,
            data: { dateStart: dateStart, dateEnd: dateEnd },
            success: function(result) { $('#kopiowanie').html(result).show(); },
            error: function(result) { alert('Błąd'); alert(result); },
        });
       return false;
    });
}
/*
function createFromPreviousYearClick() {
    $('#createFromPreviousYear').bind('click', function() {
        var dateStart = $('input#dateStart').val();
        var dateEnd = $('input#dateEnd').val();
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasy_ucznia/createFromPreviousYear",
            data: { dateStart: dateStart, dateEnd: dateEnd },
            success: function(result) { $('#kopiowanie').html(result).show(); },
            error: function(result) { alert('Błąd'); alert(result); },
        });
        return false;
    });
}
*/
function proposedDateClick() {
    $('.studentGradeDateStart').click(function(){
        $('[name="dateStart"]').val($(this).html());
        return false;
    });
    $('.studentGradeDateEnd').click(function(){
        $('[name="dateEnd"]').val($(this).html());
        return false;
    });
}
/*
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#studentGrades').animate({width: '1000px'}, 500);
        $('table#studentGrades').append('<tr id="studentGradeProposedDates2"><td colspan="7">Ładowanie formularza ...</td></tr>');
        var grade_id = $('input#grade_id').val();
        showCreateRow(grade_id);
        return false;
    });
}

function showCreateRow(grade_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasy_ucznia/create",
        data: { grade_id: grade_id, version: "forGrade" },
        success: function(result) { 
            $('table#studentGrades').append(result);
            $('tr#studentGradeProposedDates2').remove();
            addClick();
            proposedDateClick();
        },
        error: function() {
            var error = '<tr><td colspan="7"><p class="error">Błąd tworzenia wiersza zformularzem dla dodawania klasy ucznia.</p></td></tr>';
            $('table#studentGrades tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania klasy ucznia
    $('table#studentGrades').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#proposedDates').remove();
        $('#showCreateRow').show();
        return false;
    });

    $('table#studentGrades').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#proposedDates').remove();
        $('#showCreateRow').show();
        return false;
    });
}

function add() {   // zapisanie klasy ucznia w bazie danych
    var student_id = $('#createRow select[name="student_id"]').val();
    var grade_id = $('#createRow input[name="grade_id"]').val();
    var dateStart = $('#createRow input[name="dateStart"]').val();
    var dateEnd = $('#createRow input[name="dateEnd"]').val();
    var confirmationDateStart = $('#createRow input[name="confirmationDateStart"]').is(':checked');
    var confirmationDateEnd = $('#createRow input[name="confirmationDateEnd"]').is(':checked');
    if(confirmationDateStart)  confirmationDateStart=1;
    if(confirmationDateEnd)  confirmationDateEnd=1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasy_ucznia",
        data: { student_id: student_id, grade_id: grade_id, dateStart: dateStart, dateEnd: dateEnd, confirmationDateStart: confirmationDateStart, confirmationDateEnd: confirmationDateEnd },
        success: function(result) {
            $('table#studentGrades tr.create').before('<tr data-studentGradeId="'+result+'"><td colspan="7">nowy uczeń: '+result+'</td></tr>');
            refreshRow(result);
        },
        error: function() {
            var error = '<tr><td colspan="8"><p class="error">Błąd dodawania klasy dla ucznia.</p></td></tr>';
            $('table#studentGrades tr.create').after(error);
        },
    });
}
*/
function editClick() {     // kliknięcie przycisku modyfikowania klasy ucznia
    $('section#studentGrades button.edit').click(function() {
        var id = $(this).data('student_grade_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasy_ucznia/"+id+"/edit",
            data: { id: id, version: "forGrade" },
            success: function(result) {
                $('tr[data-student_grade_id='+id+']').before(result).hide();
                proposedDateClick();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem dla modyfikowania klasy ucznia.</td></tr>';
                $('tr[data-student_grade_id='+id+']').before(error);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania klasy ucznia
    $('#studentGrades .cancelUpdate').click(function(){
        var id = $(this).data('student_grade_id');
        $('.editRow[data-student_grade_id='+id+']').remove();
        $('tr[data-student_grade_id='+id+'] .proposedDates').remove();
        $('tr[data-student_grade_id='+id+']').show();
        return false;
    });

    $('#studentGrades .update').click(function(){
        var id = $(this).data('student_grade_id');
        update(id);
        return false;
    });
}

function update(id) {   // zapisanie klasy ucznia w bazie danych
    var student_id = $('tr[data-student_grade_id='+id+'] select[name="student_id"]').val();
    var grade_id = $('tr[data-student_grade_id='+id+'] input[name="grade_id"]').val();
    var date_start = $('tr[data-student_grade_id='+id+'] input[name="dateStart"]').val();
    var date_end = $('tr[data-student_grade_id='+id+'] input[name="dateEnd"]').val();
    var confirmation_date_start = $('tr[data-student_grade_id='+id+'] input[name="confirmationDateStart"]').is(':checked');
    var confirmation_date_end = $('tr[data-student_grade_id='+id+'] input[name="confirmationDateEnd"]').is(':checked');
    if(confirmation_date_start)  confirmation_date_start=1;
    if(confirmation_date_end)  confirmation_date_end=1;
    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasy_ucznia/"+id,
        data: { student_id: student_id, grade_id: grade_id, date_start: date_start, date_end: date_end, confirmation_date_start: confirmation_date_start, confirmation_date_end: confirmation_date_end },
        success: function(result) {
            $('tr[data-student_grade_id='+id+'].editRow').remove();
            $('tr[data-student_grade_id='+id+'].proposedDates').remove();
            $("tr[data-student_grade_id='"+id+"']").show();
            refreshRow(result); alert("nie działa odświeżanie");
        },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Błąd modyfikowania klasy ucznia.</td></tr>';
            $('tr[data-student_grade_id='+id+']').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie klasy ucznia (z bazy danych)
    $('.destroy').click(function() {
        var id = $(this).data('student_grade_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasy_ucznia/" + id,
            success: function() { $('tr[data-student_grade_id='+id+']').remove(); },
            error: function() {
                var error = '<tr><td colspan="8"><p class="error">Błąd usuwania klasy ucznia.</p></td></tr>';
                $('tr[data-student_grade_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}
/*
function refreshRow(id) {  // odświeżenie wiersza tabeli z klasą ucznia
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasy_ucznia/refreshRow",
        data: { id: id, version:"forGrade" },
        success: function(result) {
            $("tr[data-studentGradeId='"+id+"']").replaceWith(result);
            editClick();
            destroyClick();
        },
        error: function() {
            var error = '<td colspan="7"><p class="error">Błąd odświeżania wiersza z klasą ucznia.</p></td>';
            $("tr[data-studentGradeId='"+id+"']").html(error);
        },
    });
}


// ---------------------------- zarządzanie numerami księgi uczniów ---------------------------- //
function showCreateFormForBookOfStudentClick() {
    $('button.showCreateForm').click(function(mouse){
        $('#createForm').remove();
        var studentId = $(this).data('student_id');
        var studentGradeId = $(this).data('studentgradeid');
        showCreateFormForBookOfStudent(studentId, studentGradeId, mouse.pageX, mouse.pageY);
        return false;
    });
}

function showCreateFormForBookOfStudent(studentId, studentGradeId, X, Y) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ksiega_uczniow/create",
        data: { version: "createForm", student_id: studentId },
        success: function(result) {
            $('#main-content').append(result);
            $('#createForm').css('left', X-200);
            $('#createForm').css('top', Y-120);
            addBookOfStudentsClick(studentGradeId);
        },
        error: function() {
            var error = '<p class="error">Błąd tworzenia formularza dla dodawania numeru księgi ucznia.</p>';
            $('td#bookOfStudent').append(error);
        },
    });
}

function addBookOfStudentsClick(studentGradeId) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania numeru księgi uczniów
    $('button#cancelAddBookOfStudents').click(function(){
        $('#createForm').remove();
        return false;
    });

    $('button#addBookOfStudents').click(function(){
        addBookOfStudents();
        $('#createForm').remove();
        refreshRow(studentGradeId);
        return false;
    });
}

function addBookOfStudents() {   // zapisanie numeru księgi ucznia do bazy danych
    var student_id = $('#createForm input[name="student_id"]').val();
    var school_id = $('#createForm select[name="school_id"]').val();
    var number = $('#createForm input[name="number"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ksiega_uczniow",
        data: { student_id: student_id, school_id: school_id, number: number },
        success: function() { },
        error: function(result) {
            var error = '<p class="error">Błąd dodawania numeru księgi ucznia.</p>';
            $('td#bookOfStudent').append(error);
        },
    });
}
*/

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeButtonClick();
    dateStartOrEndChanged();
    verifyAndDisplayStudents( $('input#dateStart').val(), $('input#dateEnd').val() );
    editAllClick();
    //createFromPreviousYearClick();
    //showCreateRowClick();
    editClick();
    destroyClick();
    //showCreateFormForBookOfStudentClick();
});