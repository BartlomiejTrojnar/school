// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 13.01.2023 ------------------------ //
// ----------------- wydarzenia na stronie wyświetlania nauczanych przedmiotów ----------------- //

function showAndHiddenTeacher() {   // pokazanie lub ukrycie nauczycieli dla wybranego roku szkolnego
    var start, end, schoolYear_id;
    schoolYear_id = $('select[name="schoolYear_id"]').val();
    if(schoolYear_id) {
        $("#unlearningTeachersList li").each(function() {
            $(this).hide();
            start = parseInt($(this).children(".start").html());
            end   = $(this).children(".end").html();
            if(end != "")   end = parseInt(end);
            if(schoolYear_id>=start && schoolYear_id<=end)  $(this).show();
            if(schoolYear_id>=start && end=="")             $(this).show();
            if(schoolYear_id==0) $(this).show();
        });    
    }

    if(schoolYear_id) {
        $("#subjectTeachersList li").each(function() {
            $(this).hide();
            start = parseInt($(this).children(".start").html());
            end   = $(this).children(".end").html();
            if(end != "")   end = parseInt(end);
            if(schoolYear_id>=start && schoolYear_id<=end)  $(this).show();
            if(schoolYear_id>=start && end=="")             $(this).show();
            if(schoolYear_id==0) $(this).show();
        });    
    }
}

function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() { showAndHiddenTeacher(); },
            error: function(blad) { alert(blad); }
        });
        return false;
    });
}


// ---------------------- DODAWANIE NAUCZYCIELA do nauczanych przedmiotów ---------------------- //
// --------------------------- w widoku showTeachers dla Przedmiotu ---------------------------- //
function addTeacherDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        data.setData("teacher_id", $(this).data("teacher_id"));
        return true;
    });
}

function addTeacherDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        addTeacher(data.getData('teacher_id'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addTeacher(teacher_id) {
    var subject_id = $('div#subject_id').html();
    var teacherName = $('li[data-teacher_id=' +teacher_id+ '] data.teacherName').val();
    var start = $('li[data-teacher_id=' +teacher_id+ '] var.start').html();
    var end = $('li[data-teacher_id=' +teacher_id+ '] var.end').html();
    var li_1 = '<li class="list-group-item active" data-taughtsubject_id="';
    var li_2 = '" data-teacher_id="';
    var li_3 = '" type="button"><data class="teacherName" value="' +teacherName+ '">' +teacherName+ '</data>';
    var li_4 = '<var class="start">' +start+ '</var><var class="end">' +end+ '</var></li>';

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczany_przedmiot",
        data: { subject_id: subject_id, teacher_id: teacher_id },
        success: function(last_id) {
            $('li[data-teacher_id=' +teacher_id+ ']').remove();
            var li = li_1 + last_id + li_2 + teacher_id + li_3 + li_4;
            $('#subjectTeachersList ul').append(li);
            deleteTeacherDrag('li[data-teacher_id=' +teacher_id+ ']');
        },
        error: function(blad) { alert(92); alert('Błąd'+blad); }
    });
}

// ---------------------- USUWANIE NAUCZYCIELA do nauczanych przedmiotów ----------------------- //
// --------------------------- w widoku showTeachers dla Przedmiotu ---------------------------- //
function deleteTeacherDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        var id = $(this).data("taughtsubject_id");
        var teacherName = $('li[data-taughtsubject_id="'+id+'"] data.teacherName').val();
        data.setData("taughtSubject_id", id);
        data.setData("teacher_id", $(this).data("teacher_id"));
        data.setData("teacherName", teacherName);
        return true;
    });
}

function deleteTeacherDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var id = data.getData('taughtSubject_id');
        $('li[data-taughtsubject_id='+id+'] data.url').remove();
        deleteTeacher(id, data.getData('teacher_id'), data.getData('teacherName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteTeacher(id, teacher_id, teacherName) {
    var start = $('li[data-taughtsubject_id="' +id+ '"] .start').html();
    var end = $('li[data-taughtsubject_id="' +id+ '"] .end').html();
    var li = '<li class="list-group-item" data-teacher_id="' +teacher_id+ '" type="button">';
    li += '<data class="teacherName" value="' +teacherName+ '">' +teacherName+ '</data>';
    li += '<var class="start">' +start+ '</var><var class="end">' +end+ '</var></li>';

    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczany_przedmiot/"+id,
        success: function() {
            $('li[data-taughtsubject_id="' +id+ '"]').remove();
            $('#unlearningTeachersList ul').append( li );
            addTeacherDrag('li[data-teacher_id=' +teacher_id+ ']');
        },
        error: function(blad) { alert(blad); }
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showAndHiddenTeacher();
    schoolYearChanged();

    addTeacherDrag('#unlearningTeachersList li');
    addTeacherDrop('#subjectTeachersList');
    deleteTeacherDrag('#subjectTeachersList li');
    deleteTeacherDrop('#unlearningTeachersList');
});