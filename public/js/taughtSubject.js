// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2020 ----------------------- //
// ----------------- wydarzenia na stronie wyświetlania nauczanych przedmiotów ----------------- //


// ---------------------- DODAWANIE PRZEDMIOTU do nauczanych przedmiotów ----------------------- //
// --------------------------- w widoku showSubjects dla Nauczyciela --------------------------- //
function addSubjectDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData("subject_id", $(this).attr("data-subject-id"));
        return true;
    });
}

function addSubjectDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        addSubject(data.getData('subject_id'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addSubject(subject_id) {
    var teacher_id = $('div#teacher-id').text();
    var url = $('div#url').text();
    var token = $('div#token input').val();
    var subjectName = $('li[data-subject-id=' +subject_id+ ']').text();
    var li_1 = '<li class="list-group-item active" type="button" data-subject-name="' +subjectName+ '" data-subject-id="';
    var li_2 = '" data-taught-subject-id="';;
    var li_3 = '">' +subjectName+ '<span class="url">http://localhost/school/public/nauczany_przedmiot/delete/';
    var li_4 = '</span></li>';

    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url,
        data: { subject_id: subject_id, teacher_id: teacher_id, _token: token },
        success: function(last_id) {
            $('li[data-subject-id=' +subject_id+ ']').remove();
            var li = li_1 + subject_id + li_2 +last_id+ li_3 + last_id + li_4;
            $('#taughtSubjectsList ul').append(li);
            deleteSubjectDrag('li[data-subject-id=' +subject_id+ ']');
        },
        error: function(blad) { alert('Błąd'+blad); }
    });
}


// ----------------------- USUWANIE PRZEDMIOTU z nauczanych przedmiotów ------------------------ //
// --------------------------- w widoku showSubjects dla Nauczyciela --------------------------- //
function deleteSubjectDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        var id = $(this).attr("data-taught-subject-id");
        var url = $('li[data-taught-subject-id='+id+'] span').html();
        data.setData("taughtSubject_id", id);
        data.setData("url", url);
        data.setData("subject_id", $(this).attr("data-subject-id"));
        data.setData("subjectName", $(this).attr("data-subject-name"));
        return true;
    });
}

function deleteSubjectDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        deleteSubject(data.getData('url'), data.getData('taughtSubject_id'), data.getData('subject_id'), data.getData('subjectName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteSubject(url, id, subject_id, subjectName) {
    var token = $('div#token input').val();

    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url,
        data: { id: id, _token: token },
        success: function(dane) {
            $('li[data-taught-subject-id='+id+']').remove();
            $('#subjectsList ul').append( '<li class="list-group-item" data-subject-id="' +subject_id+ '" type="button">' +subjectName+ '</li>' );
            addSubjectDrag('li[data-subject-id=' +subject_id+ ']');
        },
        error: function(blad) { alert(blad); }
    });
}


// ---------------------- DODAWANIE NAUCZYCIELA do nauczanych przedmiotów ---------------------- //
// --------------------------- w widoku showTeachers dla Przedmiotu ---------------------------- //
function addTeacherDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        data.setData("teacher_id", $(this).attr("data-teacher-id"));
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
    var subject_id = $('div#subject-id').html();
    var url = $('div#url').text();
    var token = $('div#token input').val();
    var teacherName = $('li[data-teacher-id=' +teacher_id+ ']').text();
    var li_1 = '<li class="list-group-item active" type="button" data-teacher-name="' +teacherName+ '" data-teacher-id="';
    var li_2 = '" data-taught-subject-id="';
    var li_3 = '">' +teacherName+ '<span class="url">http://localhost/school/public/nauczany_przedmiot/delete/';
    var li_4 = '</span></li>';

    $.ajax({
        url: url,
        method: "post",
        data: { subject_id: subject_id, teacher_id: teacher_id, _token: token },
        success: function(last_id) {
            $('li[data-teacher-id=' +teacher_id+ ']').remove();
            var li = li_1 + teacher_id + li_2 +last_id+ li_3 + last_id + li_4;
            $('#subjectTeachersList ul').append(li);
            deleteTeacherDrag('li[data-teacher-id=' +teacher_id+ ']');
        },
        error: function(blad) { alert('Błąd'+blad); }
    });
}


// ---------------------- USUWANIE NAUCZYCIELA do nauczanych przedmiotów ----------------------- //
// --------------------------- w widoku showTeachers dla Przedmiotu ---------------------------- //
function deleteTeacherDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        var id = $(this).attr("data-taught-subject-id");
        var url = $('li[data-taught-subject-id='+id+'] span').html();
        data.setData("taughtSubject_id", id);
        data.setData("url", url);
        data.setData("teacher_id", $(this).attr("data-teacher-id"));
        data.setData("teacherName", $(this).attr("data-teacher-name"));
        return true;
    });
}

function deleteTeacherDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        deleteTeacher(data.getData('url'), data.getData('taughtSubject_id'), data.getData('teacher_id'), data.getData('teacherName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteTeacher(url, id, teacher_id, teacherName) {
    var token = $('div#token input').val();

    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url,
        data: { id: id, _token: token },
        success: function(dane) {
            $('li[data-taught-subject-id='+id+']').remove();
            $('#unlearningTeachersList ul').append( '<li class="list-group-item" data-teacher-id="' +teacher_id+ '" type="button">' +teacherName+ '</li>' );
            addTeacherDrag('li[data-teacher-id=' +teacher_id+ ']');
        },
        error: function(blad) { alert(blad); }
    });
}

function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rok_szkolny/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(blad) { alert(blad); }
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    addSubjectDrag('#subjectsList li');
    addSubjectDrop('#taughtSubjectsList');
    deleteSubjectDrag('#taughtSubjectsList li');
    deleteSubjectDrop('#subjectsList');

    addTeacherDrag('#unlearningTeachersList li');
    addTeacherDrop('#subjectTeachersList');
    deleteTeacherDrop('#unlearningTeachersList');
    deleteTeacherDrag('#subjectTeachersList li');

    schoolYearChanged();
});