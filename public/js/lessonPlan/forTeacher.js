// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 02.03.2022 ------------------------ //
// ---------------- wydarzenia na stronie wyświetlania planu lekcji nauczyciela ---------------- //

// -------------- pokazanie aktualnych lekcji lub ukrycie lekcji z innych terminów ------------- //
function showOrHideLesson() {
    var dateView = $('#dateView').val();
    var group_id, hours;
    $('#teacherGroups li').each(function() {
        hours = $(this).data('hours');
        $(this).children('span.hours').html(hours);
    });
    $('#teacherPlan li').each(function() {
        $(this).show();
        var teacher_id = $(this).children('.teacher').children('aside').html();
        if(teacher_id != $('#teacher_id').val())    $(this).hide();
        var start = $(this).children('.lessonDates').children('.start').html();
        if(start > dateView)    $(this).hide();
        var end = $(this).children('.lessonDates').children('.end').html();
        if(end < dateView)    $(this).hide();
        if(start <= dateView  &&  end >= dateView)  {
            group_id = $(this).data('group_id');
            hours = parseInt( $('#teacherGroups li[data-group_id="'+group_id+'"] span.hours').html() )-1;
            $('#teacherGroups li[data-group_id="'+group_id+'"] span.hours').html(hours);
            if(hours==0) $('#teacherGroups li[data-group_id="'+group_id+'"]').fadeOut(1000);
        }
    });
}

function dragTeacherGroup() {  // podnoszenie grupy nauczyciela
    $('li.teacherGroup').attr('draggable', 'true');
    $('li.teacherGroup').bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('group_id', $(this).data('group_id'));
        data.setData('type', $(this).data('type'));
        return true;
    });
}

function decreaseVisibleGroupHours(group_id) {  // funkcja odczytuje i zmniejsza liczbę godzin do obsadzenia dla grupy
    var hours = $('li.teacherGroup[data-group_id=' +group_id+ '] span.hours').html();
    hours = parseInt(hours) - 1;
    $('li.teacherGroup[data-group_id=' +group_id+ '] span.hours').html(hours);
    if(hours<1) $('li.teacherGroup[data-group_id=' +group_id+ ']').hide(1000);
}
function increaseVisibleGroupHours(group_id) {  // funkcja odczytuje i zwiększa liczbę godzin do obsadzenia dla grupy
    var hours = $('li.teacherGroup[data-group_id=' +group_id+ '] span.hours').html();
    hours = parseInt(hours) + 1;
    $('li.teacherGroup[data-group_id=' +group_id+ '] span.hours').html(hours);
    if(hours>0) $('li.teacherGroup[data-group_id=' +group_id+ ']').show(1000);
}

function dropInLessonPlan() {  // opuszczenie lekcji/grupy na planie lekcji nauczyciela
    $('#teacherPlan').delegate('td', 'drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var lessonhour_id = $(this).data('lessonhour_id');
        var dateView = $('#dateView').val();

        if( data.getData('type')=='group' ) {   // jeżeli opuszczono grupę - dodanie lekcji
            var group_id = data.getData('group_id');
            if( addLesson(group_id, lessonhour_id, dateView) )  decreaseVisibleGroupHours(group_id);
            return false;
        }
        else {
            var lesson_id = data.getData('lesson_id');
            var start = $('li[data-lesson_id="'+lesson_id+'"] .start').html();
            var old_lessonhour_id = data.getData('lessonhour_id');

            if(start == dateView) {     // tylko aktualizacja lekcji
                var group_id = $('li[data-lesson_id="'+lesson_id+'"]').data('group_id');
                var classroom_id = $('li[data-lesson_id="'+lesson_id+'"] .classroom aside').html();
                var end = $('li[data-lesson_id="'+lesson_id+'"] .end').html();
                $.when( update(lesson_id, group_id, lessonhour_id, classroom_id, start, end) ).then(function() {
                    moveLesson(lesson_id, lessonhour_id, old_lessonhour_id);
                });
            }
            else {
                if( cloneLesson(lesson_id, lessonhour_id, dateView) ) {
                    var end = changeAndFormatDate(dateView, -1);
                    setTheEndDateOfTheLesson(lesson_id, end);
                    moveLesson(lesson_id, lessonhour_id, old_lessonhour_id, dateView);
                }
            }
        }
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#teacherPlan td').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function moveLesson(lesson_id, lessonhour_id, old_lessonhour_id, start='') {
    $('li[data-lesson_id="'+lesson_id+'"]').clone().appendTo('td[data-lessonhour_id="'+lessonhour_id+'"] ul').hide();
    if(start)  {
        $('td[data-lessonhour_id="'+lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"] .start').html(start);
        var end = changeAndFormatDate(start, -1);
        $('td[data-lessonhour_id="'+old_lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"] .end').html(end);
    }
    $.when( $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000) ).then(function() {
        $('td[data-lessonhour_id="'+lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"]').fadeIn(1000);
    });
}

function update(id, group_id, lessonhour_id, classroom_id, start, end) {   // zapisanie zmian lekcji w bazie danych
    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji/"+id,
        data: { id: id, group_id: group_id, lesson_hour_id: lessonhour_id, classroom_id: classroom_id, start: start, end: end },
        success: function(result) { return result; },
        error: function() { alert('Błąd: lessonPlan/forTeacher.js - funkcja update'); return false; }
    });
}

function dragLesson() {  // podnoszenie lekcji z planu lekcji
    $('#teacherPlan li').attr('draggable', 'true');
    $('#teacherPlan').delegate('li', 'dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('lesson_id', $(this).data('lesson_id'));
        data.setData('type', $(this).data('type'));
        data.setData('group_id', $(this).data('group_id'));
        data.setData('lessonhour_id', $(this).parent().parent().data('lessonhour_id'));
        return true;
    });
}

function dropLessonInTeacherGroupList() {  // opuszczenie lekcji w polu zawierającym grupy nauczyciela
    $('#teacherGroups').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        if( data.getData('type')!='lesson' ) {
            alert( 'Upuszczono coś innego niż lekcja!' ); return false;
        }

        var lesson_id = data.getData('lesson_id');
        var end = changeAndFormatDate( $('#dateView').val(), -1);
        if( setTheEndDateOfTheLesson(lesson_id, end) ) {
            $('li[data-lesson_id="'+lesson_id+'"] .end').html(end);
            $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000);
            increaseVisibleGroupHours( data.getData('group_id') );
        }

        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#teacherGroups').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function dropLessonInCompleteRemoveField() {  // opuszczenie lekcji w polu całkowitego usunięcia
        $('#completeRemove').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        if( data.getData('type')!='lesson' ) {
            alert( 'Upuszczono coś innego niż lekcja!' ); return false;
        }
        var id = data.getData('lesson_id');
        $.when( removeLesson(id) ).then(function() {
                increaseVisibleGroupHours( data.getData('group_id') );
                $.when( $('li[data-lesson_id="'+id+'"]').fadeOut(1000) ).then(function() {
                    $('li[data-lesson_id="'+id+'"]').remove();
                });
        });
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#completeRemove').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addLesson(group_id, lessonhour_id, start) {  // wstawienie nowej lekcji dla wskazanej grupy od podanej daty
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/addLesson",
        data: { group_id: group_id, lesson_hour_id: lessonhour_id, start: start },
        success: function(id) { addLessonToTable(id, group_id, lessonhour_id, start); },
        error: function(result) { alert('Błąd: teacherPlan.js - funkcja addLesson'); alert(result); }
    });
    return true;
}

function addLessonToTable(id, group_id, lessonhour_id, start) {
    var lessonDescription = $('li[data-group_id="'+group_id+'"]').html();
    var li = '<li class="bg-warning" data-lesson_id="'+id+'" data-type="lesson" data-group_id="'+group_id+'">';
    li += lessonDescription + '</li>';
    $('td[data-lessonhour_id="'+lessonhour_id+'"] ul').html(li);
    $('li[data-lesson_id="'+id+'"] .start').html(start);
    $('li[data-lesson_id="'+id+'"] .groupDates').addClass('lessonDates').removeClass('groupDates');
    $('li[data-lesson_id="'+id+'"] .hours').remove();
    dragLesson();
}


function cloneLesson(lesson_id, lessonhour_id, start) {  // wstawienie nowej lekcji (na podstawie lekcji przeciągniętej) od podanej daty
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/cloneLesson",
        data: { lesson_id: lesson_id, lesson_hour_id: lessonhour_id, start: start },
        success: function(result) { return result; },
        error: function(result) { alert('Błąd: teacherPlan.js - funkcja cloneLesson'); alert(result); }
    });
    return true;
}

function setTheEndDateOfTheLesson(lesson_id, end) {  // ustawienie daty końcowej dla lekcji w planie
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/setTheEndDateOfTheLesson",
        data: { lesson_id: lesson_id, end: end },
        success: function() { return true; },
        error: function() { alert('Błąd: teacherPlan.js - funkcja setTheEndDateOfTheLesson'); return false; }
    });
    return true;
}

function removeLesson(lesson_id) {  // usunięcie lekcji (całkowite usunięcie rekordu z bazy danych)
    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji/"+lesson_id,
        success: function() { return true; },
        error: function(result) { alert('Błąd: teacherPlan.js - funkcja removeLesson'); alert(result); }
    });
    return true;
}

function changeAndFormatDate(date, day) {  // zmiana daty o podaną liczbę dni oraz sformatowanie jej
    newDate = new Date(date);
    newDate.setDate(newDate.getDate() + day);
    if( newDate.getMonth()<9 && newDate.getDate()<10 )
        return newDate.getFullYear() +"-0"+ (newDate.getMonth()+1) +"-0"+ newDate.getDate();
    if( newDate.getMonth()<9 )
        return newDate.getFullYear() +"-0"+ (newDate.getMonth()+1) +"-"+ newDate.getDate();
    if( newDate.getDate()<10 )
        return newDate.getFullYear() +"-"+ (newDate.getMonth()+1) +"-0"+ newDate.getDate();
    return newDate.getFullYear() +"-"+ (newDate.getMonth()+1) +"-"+ newDate.getDate();
}

// po zmianie widocznej na stronie daty widoku
function dateViewChange() {
    $('#dateView').bind('blur', function() {
        // zapamiętanie wybranej daty widoku
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rememberDates",
            data: { dateView: $('#dateView').val() },
            success: function()  {  showOrHideLesson();  },
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showOrHideLesson();
    dragTeacherGroup();
    dragLesson();
    dropInLessonPlan();
    dropLessonInTeacherGroupList();
    dropLessonInCompleteRemoveField();
    dateViewChange();
});