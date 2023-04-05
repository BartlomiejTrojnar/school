// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 04.03.2022 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania deklaracji ----------------------- //

// podnoszenie lekcji bez sali
function dragLesson()  {
    $('div.lesson').attr('draggable', 'true');
    $('div.lesson').bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('lesson_id', $(this).data('lesson_id'));
        return true;
    });
}

// podnoszenie lekcji z sali
function dragClassroomLesson()  {
    $('div.classroom-lesson').attr('draggable', 'true');
    $('div.classroom-lesson').bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('lesson_id', $(this).data('lesson_id'));
        data.setData('old_classroom_id', $(this).parent().data('classroom_id'));
        return true;
    });
}

// opuszczenie lekcji na polu sali lekcyjnej
function dropLessonInClassroom()  {
    $('div.classroom').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var classroom_id = $(this).data('classroom_id');
        var lesson_id = data.getData('lesson_id');
        var dateView = $('#dateView').val();
        var lessonDateStart = $("div[data-lesson_id="+lesson_id+"] .start").html();

        if(dateView == lessonDateStart) {
            setClassroomToLesson(lesson_id, classroom_id).then(function() {
                var lesson = $("div[data-lesson_id="+lesson_id+"]");
                $("div[data-classroom_id="+classroom_id+"]").append(lesson);
                $("div[data-lesson_id="+lesson_id+"]").removeClass('lesson');
                $("div[data-lesson_id="+lesson_id+"]").addClass('classroom-lesson');
            });
        }
        else {
            cloneLesson(lesson_id, classroom_id, dateView).then(function() {
                var end = changeAndFormatDate( dateView, -1);
                setTheEndDateOfTheLesson(lesson_id, end);
                $.when( $("div[data-lesson_id="+lesson_id+"]").fadeOut(500) ).then(function() {
                    var lesson = $("div[data-lesson_id="+lesson_id+"]");
                    $("div[data-classroom_id="+classroom_id+"]").append(lesson);
                    $("div[data-lesson_id="+lesson_id+"] .start").html(dateView);    
                    $("div[data-lesson_id="+lesson_id+"]").addClass('classroom-lesson').removeClass('lesson').fadeIn(750);
                });
                 
            });
        }
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('div.classroom').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

// opuszczenie lekcji w polu zawierającym grupy nauczyciela
function dropLessonInWithoutClassroomField()  {
    $('div.lessons').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var lesson_id = data.getData('lesson_id');
        var lessonHour_id = $('#lessonHour_id').html();
        var dateView = $('#dateView').val();
        var lessonDateStart = $("div[data-lesson_id="+lesson_id+"] .start").html();
        var old_classroom_id = (data.getData('old_classroom_id')-1)+1;

        if(dateView == lessonDateStart) {
            if(setClassroomToLesson(lesson_id, -1)) {
                var lesson = $("div[data-lesson_id="+lesson_id+"]");
                $("div.lessons").append(lesson);
                $("div[data-lesson_id="+lesson_id+"]").addClass('lesson').removeClass('classroom-lesson');
            }
        }
        else {
            var end = changeAndFormatDate( dateView, -1);
            if( cloneLesson(lesson_id, -1, dateView) ) {
                setTheEndDateOfTheLesson(lesson_id, end);
                $.when( $('div.classroom div[data-lesson_id="'+lesson_id+'"]').fadeOut(750) ).then(function() {
                    var lesson = $('div.classroom div[data-lesson_id="'+lesson_id+'"]');
                    $('div.classroom div[data-lesson_id="'+lesson_id+'"]').remove();
                    $('div.lessons').append(lesson);
                    $('div[data-lesson_id="'+lesson_id+'"]').addClass('lesson').removeClass('classroom-lesson').fadeIn(750);
                });                
            }    
        }

        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('div.lessons').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

// wstawienie nowej lekcji (na podstawie lekcji przeciągniętej) od podanej daty
function cloneLesson(lesson_id, classroom_id, start)  {
    lessonHour_id = $('#lessonHour_id').html();
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/lessonPlan/cloneLesson",
            data: { lesson_id: lesson_id, lesson_hour_id: lessonHour_id, start: start, classroom_id: classroom_id },
            success: function(result) { resolve(result); },
            error: function() { alert('Błąd: lessonHour.js - funkcja cloneLesson'); reject(0); }
        });
    });
}

// ustawienie daty końcowej dla lekcji w planie
function setClassroomToLesson(lesson_id, classroom_id)  {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/lessonPlan/setClassroomToLesson",
            data: { lesson_id: lesson_id, classroom_id: classroom_id },
            success: function(result) { resolve(result); },
            error: function() { alert('Błąd: lessonHour.js - funkcja setClassroomToLesson'); reject(0); }
        });
    });
}

// ustawienie daty końcowej dla lekcji w planie
function setTheEndDateOfTheLesson(lesson_id, end)  {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/setTheEndDateOfTheLesson",
        data: { lesson_id: lesson_id, end: end },
        success: function() { return true; },
        error: function() { alert('Błąd: lessonHour.js - funkcja setTheEndDateOfTheLesson'); return false; }
    });
    return true;
}

// zmiana daty o podaną liczbę dni oraz sformatowanie jej
function changeAndFormatDate(date, day)  {
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
function dateViewChange()  {
    $('#dateView').bind('blur', function() {
        $('div.classroom-lesson').remove();
        $('div.classroom').append('<div class="classroom-lesson">???</div>');
        // zapamiętanie wybranej daty widoku
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rememberDates",
            data: { dateView: $('#dateView').val() },
            success: function()  {  window.location.reload();  },
        });
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function()  {
    dragLesson();
    dragClassroomLesson();
    dropLessonInClassroom();
    dropLessonInWithoutClassroomField();
    dateViewChange();
});