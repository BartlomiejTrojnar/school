// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 13.01.2023 ------------------------ //
// --------- wydarzenia na stronie wyświetlania nauczanych przedmiotów dla nauczyciela --------- //

// ---------------------- DODAWANIE PRZEDMIOTU do nauczanych przedmiotów ----------------------- //
function addSubjectDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData("subject_id", $(this).data("subject_id"));
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
    var teacher_id = $('#teacher_id').val();
    var subjectName = $('li[data-subject_id=' +subject_id+ ']').text();
    var li_1  = '<li class="list-group-item active" type="button" data-subject_name="' +subjectName;
    li_1 += '" data-subject_id="' +subject_id+ '" data-taughtsubject_id="';
    var li_2 = '">' +subjectName+ '</li>';

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczany_przedmiot",
        data: { subject_id: subject_id, teacher_id: teacher_id },
        success: function(last_id) {
            $('li[data-subject_id="' +subject_id+ '"]').remove();
            var li = li_1 + last_id + li_2;
            $('#taughtSubjectsList ul').append(li);
            deleteSubjectDrag('li[data-taughtsubject_id="' +last_id+ '"]');
        },
        error: function(blad) { alert('Błąd'+blad); }
    });
}

// ----------------------- USUWANIE PRZEDMIOTU z nauczanych przedmiotów ------------------------ //
function deleteSubjectDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        var id = $(this).data("taughtsubject_id");
        data.setData("taughtSubject_id", id);
        data.setData("subject_id", $(this).data("subject_id"));
        data.setData("subjectName", $(this).data("subject_name"));
        return true;
    });
}

function deleteSubjectDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        deleteSubject(data.getData('taughtSubject_id'), data.getData('subject_id'), data.getData('subjectName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteSubject(id, subject_id, subjectName) {
    var li = '<li class="list-group-item" data-subject_id="' +subject_id+ '" type="button">' +subjectName+ '</li>';

    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczany_przedmiot/"+id,
        success: function() {
            $('li[data-taughtsubject_id="' +id+ '"]').remove();
            $('#subjectsList ul').append( li );
            addSubjectDrag('li[data-subject_id="' +subject_id+ '"]');
        },
        error: function(blad) { alert(blad); }
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    addSubjectDrag('#subjectsList li');
    addSubjectDrop('#taughtSubjectsList');
    deleteSubjectDrag('#taughtSubjectsList li');
    deleteSubjectDrop('#subjectsList');
});