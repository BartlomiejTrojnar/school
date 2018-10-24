/* (C) mgr inż. Bartłomiej Trojnar; (III) październik 2018 */
// OPERACJE W TLE STRONY dotyczące nauczanych przedmiotów  //

// dodawanie nauczania przedmiotu w widoku showSubjects dla Nauczyciela //
function addSubjectDragDrop() {
    $('#subjectsList li').attr('draggable', 'true');
    $('#subjectsList li').bind('dragstart', function(event) {    // podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        data.setData("subject_id", $(this).attr("data-subject-id"));
        data.setData("teacher_id", $(this).attr("data-teacher-id"));
        return true;
    });
    $('#taughtSubjectsList').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        addSubject(data.getData('subject_id'), data.getData('teacher_id'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#taughtSubjectsList').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addSubject(subject_id, teacher_id) {
    var url = $('div#url').text();
    var token = $('div#token input').val();
    var subjectName = $('li[data-subject-id=' +subject_id+ ']').text();
    var li_1 = '<li class="list-group-item active" type="button" data-subject-name="' +subjectName+ '" data-subject-id="';
    var li_2 = '" data-taught-subject-id="';;
    var li_3 = '">' +subjectName+ '<span class="url">http://localhost/szkola/public/nauczany_przedmiot/delete/';
    var li_4 = '</span></li>';

    $.ajax({
        url: url,
        method: "post",
        data: { subject_id: subject_id, teacher_id: teacher_id, _token: token },
        success: function(last_id) {
            $('li[data-subject-id=' +subject_id+ ']').remove();
            var li = li_1 + teacher_id + li_2 +last_id+ li_3 + last_id + li_4;
            $('#taughtSubjectsList ul').append(li);
            $('#taughtSubjectsList li').last().attr('data-subject-id', string(subject_id));
        },
        error: function(blad) { alert('Błąd'+blad); }
    });
}


// dodawanie nauczania przedmiotu w widoku showTeachers dla Przedmiotu //
function addTeacherDragDrop() {
    $('#unlearningTeachersList li').attr('draggable', 'true');
    $('#unlearningTeachersList li').bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        data.setData("subject_id", $(this).attr("data-subject-id"));
        data.setData("teacher_id", $(this).attr("data-teacher-id"));
        return true;
    });
    $('#subjectTeachersList').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        addTeacher(data.getData('subject_id'), data.getData('teacher_id'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#subjectTeachersList').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addTeacher(subject_id, teacher_id) {
    var url = $('div#url').text();
    var token = $('div#token input').val();
    var teacherName = $('li[data-teacher-id=' +teacher_id+ ']').text();
    var li_1 = '<li class="list-group-item active" type="button" data-teacher-name="' +teacherName+ '" data-teacher-id="';
    var li_2 = '" data-taught-subject-id="';;
    var li_3 = '">' +teacherName+ '<span class="url">http://localhost/szkola/public/nauczany_przedmiot/delete/';
    var li_4 = '</span></li>';

    $.ajax({
        url: url,
        method: "post",
        data: { subject_id: subject_id, teacher_id: teacher_id, _token: token },
        success: function(last_id) {
            $('li[data-teacher-id=' +teacher_id+ ']').remove();
            var li = li_1 + subject_id + li_2 +last_id+ li_3 + last_id + li_4;
            $('#subjectTeachersList ul').append(li);
            $('#subjectTeachersList li').last().attr('data-teacher-id', string(teacher_id));
        },
        error: function(blad) { alert('Błąd'+blad); }
    });
}


// usuwanie nauczania przedmiotu w widoku showSubjects dla Nauczyciela //
function deleteSubjectDragDrop() {
    $('#taughtSubjectsList li').attr('draggable', 'true');
    $('#taughtSubjectsList li').bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        var id = $(this).attr("data-taught-subject-id");
        var url = $('li[data-taught-subject-id='+id+'] span').html();
        data.setData("taughtSubject_id", id);
        data.setData("url", url);
        data.setData("subject_id", $(this).attr("data-subject-id"));
        data.setData("subjectName", $(this).attr("data-subject-name"));
        return true;
    });
    $('#subjectsList').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        deleteSubject(data.getData('url'), data.getData('taughtSubject_id'), data.getData('subject_id'), data.getData('subjectName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#subjectsList').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteSubject(url, id, subject_id, subjectName) {
    $.ajax({
        url: url,
        method: "get",
        data: { id: id },
        success: function(dane) {
            $('li[data-taught-subject-id='+id+']').remove();
            $('#subjectsList ul').append( '<li class="list-group-item" data-subject-id="' +subject_id+ '" type="button">' +subjectName+ '</li>' );
        },
        error: function(blad) { alert(blad); }
    });
}


// usuwanie nauczania przedmiotu w widoku showTeachers dla Przedmiotu //
function deleteTeacherDragDrop() {
    $('#subjectTeachersList li').attr('draggable', 'true');
    $('#subjectTeachersList li').bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        var id = $(this).attr("data-taught-subject-id");
        var url = $('li[data-taught-subject-id='+id+'] span').html();
        data.setData("taughtSubject_id", id);
        data.setData("url", url);
        data.setData("teacher_id", $(this).attr("data-teacher-id"));
        data.setData("teacherName", $(this).attr("data-teacher-name"));
        return true;
    });
    $('#unlearningTeachersList').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        deleteTeacher(data.getData('url'), data.getData('taughtSubject_id'), data.getData('teacher_id'), data.getData('teacherName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#unlearningTeachersList').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteTeacher(url, id, teacher_id, teacherName) {
    $.ajax({
        url: url,
        method: "get",
        data: { id: id },
        success: function(dane) {
            $('li[data-taught-subject-id='+id+']').remove();
            $('#unlearningTeachersList ul').append( '<li class="list-group-item" data-teacher-id="' +teacher_id+ '" type="button">' +teacherName+ '</li>' );
        },
        error: function(blad) { alert(blad); }
    });
}


// ------------------------- załadowanie dokumentu ------------------------- //
$(document).ready(function() {
    deleteTeacherDragDrop();
    addTeacherDragDrop();
    deleteSubjectDragDrop();
    addSubjectDragDrop();
});