// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 12.09.2022 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania grup ucznia ---------------------- //

function showOrHideGroups() {
    var dateView = $('#dateView').val();
    var count=0, countStudents=0;
    var groupGrades = [];
    var hide=false;
    $('#groupGrades button.off').each(function() {
        groupGrades[count++] = $(this).data('grade_id');
    });

    $('#studentsListForGroup li').each(function() {    // sprawdzenie i wyświetlenie odpowiednich uczniów
        $(this).removeClass('hide');
        hide = false;
        if( $(this).data('start') > dateView )  hide=true;
        if( $(this).data('end') < dateView )    hide=true;
        if( $(this).data('grade_start') > dateView )  hide=true;
        if( $(this).data('grade_end') < dateView )    hide=true;
        for(i=0; i<count; i++)  if(groupGrades[i] == $(this).data('grade_id'))  hide=true;
        if(hide) $(this).addClass('hide');
        else countStudents++;
    });
    $('#countStudents').html(countStudents);

    $('#listGroupStudentsInOtherTime li').each(function() {    // sprawdzenie i wyświetlenie odpowiednich uczniów
        $(this).addClass('hide');
        if( $(this).data('start') > dateView )  $(this).removeClass('hide');
        if( $(this).data('end') < dateView )    $(this).removeClass('hide');
        if( $(this).data('grade_start') > dateView )  $(this).addClass('hide');
        if( $(this).data('grade_end') < dateView )    $(this).addClass('hide');
        for(i=0; i<count; i++)  if(groupGrades[i] == $(this).data('grade_id'))  $(this).addClass('hide');
    });

    $('#listOutsideGroupStudents li').each(function() {    // sprawdzenie i wyświetlenie odpowiednich uczniów
        $(this).removeClass('hide');
        if( $(this).data('grade_start') > dateView )  $(this).addClass('hide');
        if( $(this).data('grade_end') < dateView )    $(this).addClass('hide');
        for(i=0; i<count; i++)  if(groupGrades[i] == $(this).data('grade_id'))  $(this).addClass('hide');
    });
}

function dateViewChange() {     // po zmianie widocznej na stronie daty widoku
    $('#dateView').bind('blur', function() {
        var dateView = $('#dateView').val();
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rememberDates",
            data: { dateView: dateView },
            success: function()  {
                showOrHideGroups();
                var group_id = $('#group_id').val();
                $('#listOutsideGroupStudents').html('<li>aktualizacja...</li>');
                refreshOutsideGroupStudentsList(group_id, dateView);
            },
        });
    });
}

function refreshOutsideGroupStudentsList(group_id, dateView) {      // odświeżenie listy uczniów nienależących dla wybranej daty do grupy
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/groupStudent/getOutsideGroupStudents",
        data: { group_id: group_id, dateView: dateView },
        success: function(result) {
            $('ul#listOutsideGroupStudents').replaceWith( result );
            $('ul#listOutsideGroupStudents li').slideDown(2000);
            outsideGroupStudentClick();
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja refreshOutsideGroupStudentsList'); alert(result); }
    });
}

function outsideGroupStudentClick() {  // kliknięcie w ucznia nienależącego do grupy: zaznaczenie ucznia
    $('#listOutsideGroupStudents').delegate('li', 'click', function(){
        if( $(this).hasClass('checked') ) {
            $(this).animate({ marginLeft: "-=10px"}, "slow").removeClass('checked');
            $(this).children('span').remove();
        }
        else
            $(this).animate({ marginLeft: "+=10px"}, "slow").addClass('checked').prepend('<span class="glyphicon glyphicon-ok"></span>');
            $(this).animate({ backgroundColor: "#004400" }, 500);
        return false;
    });
}

function checkTheValuesForAddStudentsToGroup(group_id, start, end) {  // funkcja sprawdza czy ustawiono prawidłowe daty by wprowadzić ucznia do grupy
    $('p.btn-danger').remove();
    var error=false;
    if(!group_id) {
        $('#buttons').after('<p class="btn btn-danger">Nie wybrano ucznia lub grupy</p>');
        error=true;
    }
    if(start < $('#groupStart').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data początkowa jest wcześniejsza niż data początkowa grupy!</p>');
        error=true;
    }
    if(start > $('#groupEnd').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data początkowa jest późniejsza niż data końcowa grupy!</p>');
        error=true;
    }
    if(end > $('#groupEnd').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data końcowa jest późniejsza niż data końcowa grupy!</p>');
        error=true;
    }
    if(start=='0000-00-00' || end=='0000-00-00' || !start || !end) {
        $('#buttons').after('<p class="btn btn-danger">Nie ustawiono daty początkowej lub daty końcowej</p>');
        error=true;
    }
    if(start > end) {
        $('#buttons').after('<p class="btn btn-danger">Data początkowa jest późniejsza niż data końcowa</p>');
        error=true;
    }
    $('p.btn-danger').fadeOut(7500);
    return error;
}

function addStudent(student_id, group_id, start, end) {  // dodanie serii uczniów do grupy wg podanych dat
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/groupStudent/addStudent",
        data: { group_id: group_id, student_id: student_id, start: start, end: end },
        success: function(result) {
            $('li[data-student_id="'+student_id+'"').hide(2000);
            $("#studentsListForGroup ol").append(result);
        },
        error: function() { alert('Błąd: groupStudent.js - funkcja addStudent'); }
    });
}

function addCheckedStudentClick() {  // kliknięcie w przycisk "Dodaj zaznaczonych"
    $('#addCheckedStudents').bind('click', function() {
        // pobranie aktualnych danych (informacji dotyczących przynależności ucznia do grupy)
        var group_id = $('#group_id').val();
        var start = $('#dateView').val();
        var end = $('#groupEnd').html();
        // sprawdzenie czy dane są prawidłowe
        if( checkTheValuesForAddStudentsToGroup(group_id, start, end) ) return false;

        var count = $('#listOutsideGroupStudents li.checked').length;
        if(count<=5) {
            // dodanie każdego znalezionego ucznia do grupy
            $('#listOutsideGroupStudents li.checked').each(function() {
                student_id = $(this).data('student_id');
                addStudent(student_id, group_id, start, end);
            });
        }
        else {
            var students = [];
            count = 0;
            // zapamiętanie wszystkich uczniów
            $('#listOutsideGroupStudents li.checked').each(function() {  students[count++] = $(this).data('student_id');  });
            addStudentsToGroup(students, group_id, start, end);    
        }
    });
}

function addStudentsToGroup(students, group_id, start, end) {  // dodanie ucznia do grupy wg podanych dat
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/groupStudent/addManyStudent",
        data: { group_id: group_id, students: students, start: start, end: end },
        success: function(result) {
            location.reload();
        },
        error: function() { alert('Błąd: groupStudent.js - funkcja addStudentToGroup'); }
	});
}

function addAllStudentClick() {  // kliknięcie w przycisk "Dodaj wszystkich"
    $('#addAllStudents').bind('click', function() {
        // pobranie aktualnych danych (informacji dotyczących przynależności ucznia do grupy)
        var group_id = $('#group_id').val();
        var start = $('#dateView').val();
        var end = $('#groupEnd').html();
        var students = [];
        var count = 0;
        // sprawdzenie czy dane są prawidłowe
        if( checkTheValuesForAddStudentsToGroup(group_id, start, end) ) return false;
        // zapamiętanie wszystkich uczniów
        dateView = $('#dateView').val();
        $('#listOutsideGroupStudents li').each(function() {
            if( $(this).data('grade_start')<=dateView && $(this).data('grade_end')>=dateView ) {
                students[count++] = $(this).data('student_id');
            }
        });
        if(count)   addStudentsToGroup(students, group_id, start, end);
        else alert('Brak uczniów do dodania');
    });
}

function groupStudentEditClick()  {     // kliknięcie w przycisk modyfikowania ucznia w grupie
    $('div').delegate('.edit', 'click', function(mouse) {
        var group_student_id = $(this).data('group_student_id');
        // pokazanie formularza edycji ucznia w grupie
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie/"+group_student_id+"/edit",
            data: { version: "forGroup" },
            success: function(result)  { 
                $('#main-content').append(result);
                $('aside#studentEditForm').css('top', mouse.pageY-300);
                $('aside#studentEditForm').css('left', mouse.pageX-150);
                editFormClick();
            },
            error: function(result) { alert('Błąd: groupStudent.js - funkcja groupStudentEditClick'); alert(result); return false; }
        });
        return false;
    });
}

function editFormClick() {
    $('.cancelUpdate').click(function() {
        $('#studentEditForm').remove();
        return false;
    });
    $('.update').click(function(){
        update( $(this).data('student_group_id') );
        return false;
    });
    // propozycje dat - reakcja po kliknięciu
    $('#proposedDateStart span.proposedDate').click(function() {
        $('input#start').val($(this).html());
        var start = $('input#start').val();
        var end = $('input#end').val();
        datesValidate(start, end);
    });
    // propozycje dat - reakcja po kliknięciu
    $('#proposedDateEnd span.proposedDate').click(function() {
        $('input#end').val($(this).html());
        var start = $('input#start').val();
        var end = $('input#end').val();
        datesValidate(start, end);
    });

    $('input#start').change(function() {
        var start = $(this).val();
        var end = $('input#end').val();
        datesValidate(start, end);
        return false;
    });

    $('input#end').change(function() {
        var start = $('input#start').val();
        var end = $(this).val();
        datesValidate(start, end);
        return false;
    });
}

function datesValidate(start, end) {      // sprawdzenie czy daty są prawidłowe
    $('td.error').html('');
    $('button.update').removeClass('disabled');
    if( start == '' ) {
      $('td.error').html('data początkowa nie może być pusta');
      $('button.update').addClass('disabled');
    }
    if( end == '' ) {
      $('td.error').html('data końcowa nie może być pusta');
      $('button.update').addClass('disabled');
    }
    if( start > end ) {
      $('td.error').html('data początkowa nie może być późniejsza niż data końcowa');
      $('button.update').addClass('disabled');
    }
    if( start < $('#groupStart').html() ) {
      $('td.error').html('data początkowa nie może być wcześniejsza niż data początkowa grupy');
      $('button.update').addClass('disabled');
    }
    if( end > $('#groupEnd').html() ) {
      $('td.error').html('data końcowa nie może być późniejsza niż data końcowa grupy');
      $('button.update').addClass('disabled');
    }
}


function showOrHideOneStudent(group_student_id, start, end) {
    $('#groupStudentDeleteForm').remove();
    $('#studentEditForm').remove();
    var dateView = $('#dateView').val();
    $("li[data-group_student_id='"+group_student_id+"']").attr('data-start', start);
    $("li[data-group_student_id='"+group_student_id+"']").attr('data-end', end);
    $("li[data-group_student_id='"+group_student_id+"'] span.period").html(start+' - '+end);
    if(start>dateView || end<dateView) {
        $("#studentsListForGroup li[data-group_student_id='"+group_student_id+"']").addClass('hide');
        $("#listGroupStudentsInOtherTime li[data-group_student_id='"+group_student_id+"']").removeClass('hide');
        $("#countStudents").html( $("#countStudents").html()-1 );
    }
    else {
        alert(300);
        $("#studentsListForGroup li[data-group_student_id='"+group_student_id+"']").removeClass('hide');
        $("#listGroupStudentsInOtherTime li[data-group_student_id='"+group_student_id+"']").addClass('hide');
    }
}

function update(id) {   // zapisanie zmian przynależności ucznia do grupy w bazie danych
    var group_id =   $('#studentEditForm input[name="group_id"]').val();
    var student_id = $('#studentEditForm input[name="student_id"]').val();
    var start = $('#studentEditForm input[name="start"]').val();
    var end =   $('#studentEditForm input[name="end"]').val();
    if(checkTheValuesForAddStudentsToGroup(group_id, start, end)) {
        $('#studentEditForm').remove();
        return;
    }

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/grupa_uczniowie/"+id,
        data: { id: id, group_id: group_id, student_id: student_id, start: start, end: end },
        success: function() {  showOrHideOneStudent(id, start, end);  },
        error: function() {  alert('Błąd modyfikowania przynależności ucznia do grupy.');  },
    });
}

function completeRemoveClick() {    // całkowite usunięcie ucznia z grupy
    $('#groupStudentDeleteForm .completeRemove').click(function() {
        var group_student_id = $('.completeRemove').data('group_student_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie/" + group_student_id,
            data: { },
            success: function() {
                $('#groupStudentDeleteForm').remove();
                $('li[data-group_student_id=' +group_student_id+ ']').remove();
                var group_id = $('#group_id').val();
                var dateView = $('#dateView').val();
                refreshOutsideGroupStudentsList(group_id, dateView);
            },
            error: function() { alert('Błąd: groupStudent.js - funkcja removeStudentFromGroup'); }
        });
    });
}

function removeFromDate() {       // usunięcie ucznia z grupy od wybranego dnia
    $('#removeFromDate').bind('click', function() {
        var group_student_id = $(this).data('group_student_id');
        var group_id = $('#group_id').val();
        var start = $("li[data-group_student_id='"+group_student_id+"']").data('start');
        var end = $('input[name="end"]').val();
        if(checkTheValuesForAddStudentsToGroup(group_id, start, end)) {
            $('#groupStudentDeleteForm').remove();
            return;
        }

        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie/updateEnd/" + group_student_id,
            data: { end: end  },
            success: function()  {  showOrHideOneStudent(group_student_id, start, end);  },
            error: function(result) { alert('Błąd: groupStudent.js - funkcja removeFromDate'); alert(result); return false; }
        });
        return false;
    }); 
}

function groupStudentDeleteClick()  {   // kliknięcie w przycisk usuwania ucznia z grupy
    $('div').delegate('.delete', 'click', function(e) {
        var group_student_id = $(this).data('group_student_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie/delete/"+group_student_id,
            data: {  },
            success: function(result)  {
                $('#main-content').append(result);
                $('#groupStudentDeleteForm').css('border', '4px solid white');
                $('#groupStudentDeleteForm').css('background', '#aaf');
                $('#groupStudentDeleteForm').css('position', 'absolute');
                $('#groupStudentDeleteForm').css('left', e.pageX-350);
                $('#groupStudentDeleteForm').css('top', e.pageY-250);
                completeRemoveClick();
                removeFromDate();
            },
            error: function(result) { alert('Błąd: groupStudent.js - funkcja groupStudentDeleteClick'); alert(result); return false; }
        });
        return false;
    });
}

function gradeClick() {
    $("#groupGrades button").click(function() {
        if( $(this).hasClass('on') )   $(this).addClass('off').removeClass('on');
        else $(this).removeClass('off').addClass('on');
        showOrHideGroups();
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showOrHideGroups();
    dateViewChange();
    outsideGroupStudentClick();
    addAllStudentClick();
    addCheckedStudentClick();
    groupStudentEditClick();
    groupStudentDeleteClick();
    gradeClick();
});