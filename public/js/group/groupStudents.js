// --------------------- (C) mgr inż. Bartłomiej Trojnar; (III) listopad 2020 --------------------- //
// ----------------------- wydarzenia na stronie wyświetlania grup ucznia ----------------------- //

function outsideGroupStudentClick() {  // kliknięcie w ucznia nienależącego do grupy: zaznaczenie ucznia
    $('#outsideGroupStudents').delegate('li', 'click', function(){
        if( $(this).hasClass('checked') ) {
            $(this).animate({ marginLeft: "-=10px"}, "slow").removeClass('checked');
            $(this).children('span').remove();
        }
        else
            $(this).animate({ marginLeft: "+=10px"}, "slow").addClass('checked').prepend('<span class="glyphicon glyphicon-ok"></span>');
        return false;
    });
}

function addAllStudentClick() {  // kliknięcie w przycisk "Dodaj wszystkich"
    $('#addAllStudents').bind('click', function() {
        var group_id = $('#group_id').val();
        var dateStart = $('#dateView').val();
        var dateEnd = $('#groupDateEnd').html();
        var students = [];
        var count = 0;
        if(dateStart>dateEnd) { alert('Data początkowa jest późniejsza niż data końcowa'); return; }
        $('#outsideGroupStudents li').each(function() {
            students[count++] = $(this).data('student_id');
        });

        addStudentsToGroup(students, group_id, dateStart, dateEnd);
        refreshGroupStudentsList(group_id, dateStart);
        refreshOutsideGroupStudentsList(group_id, dateStart);
    });
}

function addCheckedStudentClick() {  // kliknięcie w przycisk "Dodaj zaznaczonych"
    $('#addCheckedStudents').bind('click', function() {
        var group_id = $('#group_id').val();
        var dateStart = $('#dateView').val();
        var dateEnd = $('#groupDateEnd').html();
        var students = [];
        var count = 0;

        $('#outsideGroupStudents li.checked').each(function() {
            student_id = $(this).data('student_id');
            students[count++] = student_id;
            $("li[data-student_id="+student_id+"]").remove();
        });

        addStudentsToGroup(students, group_id, dateStart, dateEnd);
        //refreshGroupStudentsList(group_id, dateStart);
        //refreshOutsideGroupStudentsList(group_id, dateStart);
    });
}

function addStudentDrag() {
        $('#outsideGroupStudents li').attr('draggable', 'true');
        $('#outsideGroupStudents').delegate('li', 'dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData("student_id", $(this).attr("data-student_id"));
        return true;
    });
}
function addStudentDrop() {
    $('div').delegate('#groupStudents', 'drop', function(event) {
        //pobranie danych dla wpisania ucznia do grupy
        var data = event.originalEvent.dataTransfer;
        var student_id = data.getData("student_id");
        var group_id = $('#group_id').val();
        var dateStart = $('#dateView').val();
        var dateEnd = $('#groupDateEnd').html();

        // wprowadzenie ucznia do grupy
        addStudentToGroup(student_id, group_id, dateStart, dateEnd);
        $("li[data-student_id="+student_id+"]").remove();
        $('#groupStudents li').slideUp(1500);
        setTimeout( function()  { refreshGroupStudentsList(group_id, $('#dateView').val()); }, 1500);

        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#groupStudents').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addStudentsToGroup(students, group_id, dateStart, dateEnd) {  // dodanie serii uczniów do grupy wg podanych dat
    if( checkTheValuesForAddStudentsToGroup(group_id, dateStart, dateEnd) ) return false;

    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie/addManyStudents",
        data: { group_id: group_id, students: students, date_start: dateStart, date_end: dateEnd },
        success: function(result) {
            refreshOutsideGroupStudentsList(group_id, dateStart);
            refreshGroupStudentsList(group_id, dateStart);
            //setTimeout( function()  { refreshGroupStudentsList(group_id, dateStart); }, 1500);
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja addStudentsToGroup'); }
	});
}
function checkTheValuesForAddStudentsToGroup(group_id, dateStart, dateEnd) {  // funkcja sprawdza czy ustawiono prawidłowe daty by wprowadzić ucznia do grupy
    $('p.btn-danger').remove();
    var error=false;
    if(!group_id) {
        $('#buttons').after('<p class="btn btn-danger">Nie wybrano ucznia lub grupy</p>');
        error=true;
    }
    if(dateStart < $('#groupDateStart').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data początkowa jest wcześniejsza niż data początkowa grupy!</p>');
        error=true;
    }
    if(dateStart > $('#groupDateEnd').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data początkowa jest późniejsza niż data końcowa grupy!</p>');
        error=true;
    }
    if(dateStart=='0000-00-00' || dateEnd=='0000-00-00' || !dateStart || !dateEnd) {
        $('#buttons').after('<p class="btn btn-danger">Nie ustawiono daty początkowej lub daty końcowej</p>');
        error=true;
    }
    if(dateStart > dateEnd) {
        $('#buttons').after('<p class="btn btn-danger">Data początkowa jest późniejsza niż data końcowa</p>');
        error=true;
    }
    $('p.btn-danger').fadeOut(7500);
    return error;
}

function addStudentToGroup(student_id, group_id, dateStart, dateEnd) {  // dodanie ucznia do grupy wg podanych dat
    if( checkTheValuesForAddStudentToGroup(student_id, group_id, dateStart, dateEnd) ) return false;

    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie",
        data: { group_id: group_id, student_id: student_id, date_start: dateStart, date_end: dateEnd },
        success: function(result) {  },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja addStudentToGroup'); }
	});
}

function checkTheValuesForAddStudentToGroup(student_id, group_id, dateStart, dateEnd) {  // funkcja sprawdza czy ustawiono prawidłowe daty by wprowadzić ucznia do grupy
    $('p.btn-danger').remove();
    var error=false;
    if(!student_id || !group_id) {
        $('#main-content').prepend('<p class="btn btn-danger">Nie wybrano ucznia lub grupy</p>');
        error=true;
    }
    if(dateStart=='0000-00-00' || dateEnd=='0000-00-00' || !dateStart || !dateEnd) {
        $('#main-content').prepend('<p class="btn btn-danger">Nie ustawiono daty początkowej lub daty końcowej</p>');
        error=true;
    }
    if(dateStart > dateEnd) {
        $('#main-content').prepend('<p class="btn btn-danger">Data początkowa jest późniejsza niż data końcowa</p>');
        error=true;
    }
    if(dateStart < $('#groupDateStart').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data początkowa jest wcześniejsza niż data początkowa grupy!</p>');
        error=true;
    }
    if(dateStart > $('#groupDateEnd').html()) {
        $('#buttons').after('<p class="btn btn-danger">Ustawiona data początkowa jest późniejsza niż data końcowa grupy!</p>');
        error=true;
    }
    $('p.btn-danger').fadeOut(10000);
    return error;
}

function deleteStudentDrag() {      // podniesienie belki z uczniem z grupy
    $('#groupStudents li').attr('draggable', 'true');
    $('#groupStudents').delegate('li', 'dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData("groupStudent_id", $(this).attr("data-groupStudent_id"));
        return true;
    });
}
function deleteStudentDrop() {      // opuszczenie belki z uczniem na pole uczniów nienależących do grupy
    $('#outsideGroupStudents').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        removeStudentFromGroup( data.getData("groupStudent_id") );
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#outsideGroupStudents').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}
function removeStudentFromGroup(groupStudent_id) {
    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie/delete/" +groupStudent_id,
        data: { },
        success: function(result) {
            $('li[data-groupStudent_id=' +groupStudent_id+ ']').remove();
            var group_id = $('#group_id').val();
            var dateView = $('#dateView').val();
            refreshOutsideGroupStudentsList(group_id, dateView);
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja removeStudentFromGroup'); }
    });
}

function refreshGroupStudentsList(group_id, dateView) {     // odświeżenie listy uczniów należących do grupy we wskazanym czasie
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie/getGroupStudents",
        data: { group_id: group_id, dateView: dateView },
        success: function(result) {
            $('ol#groupStudents').replaceWith( result );
            $('ol#groupStudents li').slideUp( 0 ).slideDown(1000);
            var countStudents=0;
            $('ol#groupStudents li').each( function() { countStudents++; } );
            $('#countStudents').html( countStudents );
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja refreshGroupStudentsList'); alert(result); }
    });
}

function refreshAnotherTimeGroupStudentsList(group_id, dateView) {      // odświeżenie listy uczniów którzy należeli do grupy w innym czasie
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie/getAnotherTimeGroupStudents",
        data: { group_id: group_id, dateView: dateView },
        success: function(result) {
            $('ul#anotherTimeGroupStudents').replaceWith( result );
            $('ul#anotherTimeGroupStudents li').slideUp( 0 ).slideDown(2000);
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja refreshAnotherTimeGroupStudentsList'); }
    });
}

function refreshOutsideGroupStudentsList(group_id, dateView) {      // odświeżenie listy uczniów nienależących dla wybranej daty do grupy
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie/getOutsideGroupStudents",
        data: { group_id: group_id, dateView: dateView },
        success: function(result) {
            $('ul#outsideGroupStudents').replaceWith( result );
            $('ul#outsideGroupStudents li').slideDown(2000);
            outsideGroupStudentClick();
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja refreshOutsideGroupStudentsList'); alert(result); }
    });
}

function refreshStudentGroupsList(student_id, dateView) {     // odświeżenie listy grup ucznia we wskazanym czasie
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/grupa_uczniowie/getStudentGroups",
        data: { student_id: student_id, dateView: dateView },
        success: function(result) {
            $('ul#studentGroups').replaceWith( result );
            $('ul#studentGroups li').slideUp( 0 ).slideDown(1000);
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja refreshStudentGroupsList'); alert(result); }
    });
}

function dateViewChange() {     // po zmianie widocznej na stronie daty widoku
    $('#dateView').bind('blur', function() {
        var dateView = $('#dateView').val();
        sessionDateViewPut(dateView);
        if($('ul#studentGroups')) { // wykonywane dla widoku grup dla ucznia (jeżeli istnieje ul#studentGroups)
            var student_id = $('input[name="student_id"]').val();
            setTimeout( function()  { refreshStudentGroupsList(student_id, dateView); }, 150);
            return;
        }

        $('ul#groupStudents').html('<li>aktualizacja...</li>');
        $('ul#anotherTimeGroupStudents').html('<li>aktualizacja...</li>');
        $('ul#outsideGroupStudents').html('<li>aktualizacja...</li>');

        var group_id = $('#group_id').val();
        refreshGroupStudentsList(group_id, dateView);
        refreshAnotherTimeGroupStudentsList(group_id, dateView);
        refreshOutsideGroupStudentsList(group_id, dateView);
    });
}

function sessionDateViewPut(dateView) {     // zapamiętanie wybranej daty jako daty widoku w sesji
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/rememberDates",
        data: { dateView: dateView },
    });
}

function groupStudentEditClick()  {     // kliknięcie w przycisk modyfikowania ucznia w grupie
    $('div').delegate('.groupStudentEdit', 'click', function(mouse) {
        showStudentGroupEditForm( $(this).attr('href'), mouse.pageX, mouse.pageY );
        return false;
    });
    $('ul#studentGroups').delegate('.groupStudentEdit', 'click', function(mouse) {
        showStudentGroupEditForm( $(this).attr('href'), mouse.pageX, mouse.pageY );
        return false;
    });
}

function showStudentGroupEditForm(url, X, Y) {      // pokazanie formularza edycji ucznia w grupie
    $.ajax({
        type: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url,
        success: function(result)  { 
            $('#main-content').append(result);
            $('aside#studentEditForm').css('top', Y-300);
            $('aside#studentEditForm').css('left', X-150);
        },
        error: function(result) { alert('Błąd: groupStudent.js - funkcja groupStudentEditClick'); alert(result); return false; }
    });
}

function groupStudentDeleteClick()  {   // kliknięcie w przycisk usuwania ucznia z grupy
    $('div').delegate('.groupStudentDelete', 'click', function() {
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $(this).attr('href'),
            data: {  },
            success: function(result)  {
                $('#main-content').append(result);
                removeFromDate();
            },
            error: function(result) { alert('Błąd: groupStudent.js - funkcja groupStudentDeleteClick'); alert(result); return false; }
        });
        return false;
    });
}

function removeFromDate() {       // usunięcie ucznia z grupy od wybranego dnia
    $('#removeFromDate').bind('click', function() {
        var group_id = $('input[name="group_id"]').val();
        var dateEnd = $('input[name="date_end"]').val();
        var dateView = $('#dateView').val();
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $(this).attr('href'),
            data: { date_end: dateEnd  },
            success: function(result)  {
                if(result) {
                    $('#groupStudentDeleteForm').remove();
                    refreshGroupStudentsList(group_id, dateView);
                    refreshAnotherTimeGroupStudentsList(group_id, dateView);
                }
            },
            error: function(result) { alert('Błąd: groupStudent.js - funkcja usunDnia'); alert(result); return false; }
        });
        return false;
    }); 
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    alert(362);
    outsideGroupStudentClick();
    addAllStudentClick();
    addCheckedStudentClick();
    addStudentDrag();
    addStudentDrop();
    deleteStudentDrag();
    deleteStudentDrop();
    dateViewChange();
    groupStudentEditClick();
    groupStudentDeleteClick();
});