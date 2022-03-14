// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 24.02.2022 ------------------------ //
// ------------------- wydarzenia na stronie wyświetlania planu lekcji grupy ------------------- //


// -------------- pokazanie aktualnych lekcji lub ukrycie lekcji z innych terminów ------------- //
function showOrHideLesson() {
    var dateView = $('#dateView').val();
    $('#groupPlan li').each(function() {
        $(this).show();
        var start = $(this).children('.lessonDates').children('.start').html();
        if(start > dateView)    $(this).hide();
        var end = $(this).children('.lessonDates').children('.end').html();
        if(end < dateView)    $(this).hide();
        if(start <= dateView  &&  end >= dateView)  $('#remainedHours').html( $('#remainedHours').html()-1 );
    });
    if( $('#remainedHours').html()<1 )  {
        $('td.lesson button').fadeOut(1000);
        $('#groupHours').css('background', '#a33');
    }
    else {
        $('td.lesson button').fadeIn(1000);
        $('#groupHours').css('background', '#888');
    }
}

// kliknięcie znaku '+' w komórce w tabeli z planem lekcji: pobranie danych i dodanie lekcji
function tdLessonAddButtonClick() {
    $('td.lesson button').bind('click', function() {
        var lessonhour_id = $(this).data('lessonhour_id');
        var group_id = $('#group_id').val();
        var start =  $('#dateView').val();
        if( addLesson(group_id, lessonhour_id, start) )     setTimeout(function() {  
            findGroupLessonForHour(lessonhour_id);
            $('#remainedHours').html( $('#remainedHours').html()-1 );
            if( $('#remainedHours').html()<1 )  {
                $('td.lesson button').fadeOut(1000);
                $('#groupHours').css('background', '#a33');
            }
        }, 250);
        return false;
    });
}

// wstawienie nowej lekcji dla wskazanej grupy od podanej daty
function addLesson(group_id, lessonhour_id, start) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/addLesson",
        data: { group_id: group_id, lesson_hour_id: lessonhour_id, start: start },
        success: function(result) { return result; },
        error: function(result) { alert('Błąd: teacherPlan.js - funkcja addLesson'); alert(result); }
    });
    return true;
}

// znalezienie lekcji grupy dla podanej godziny oraz wpisanie jej do tabeli
function findGroupLessonForHour(lessonHour, group_id=0, dateView=0) {
    if(!group_id)  group_id = $('#group_id').val();
    if(!dateView)  dateView = $('#dateView').val();
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/findGroupLessonForHour",
        data: { group_id: group_id, lesson_hour_id: lessonHour, dateView: dateView },
        success: function(result) {
            $('td[data-lessonhour_id="'+lessonHour+'"] ul').html( result );
            dragLesson();
        },
        error: function() { alert('Błąd: groupPlan.js - funkcja findGroupLessonForHour'); alert(lessonHour); }
    });
    return true;
}

// podnoszenie lekcji z planu lekcji
function dragLesson() {
    $('#groupPlan li').attr('draggable', 'true');
    $('#groupPlan').delegate('td.lesson li', 'dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('lesson_id', $(this).data('lesson_id'));
        data.setData('lessonhour_id', $(this).parent().parent().data('lessonhour_id'));
        return true;
    });
}

// opuszczenie lekcji na planie lekcji grupy
function dropLessonInLessonPlan() {
    $('#groupPlan td').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var lessonhour_id = $(this).data('lessonhour_id');
        var dateView = $('#dateView').val();
        var lesson_id = data.getData('lesson_id');
        var start = $('li[data-lesson_id="'+lesson_id+'"] .start').html();
        var old_lessonhour_id = data.getData('lessonhour_id');
        
        if(start == dateView) {
            var group_id = $('#group_id').val();
            var classroom_id = $('li[data-lesson_id="'+lesson_id+'"] .classroom aside').html();
            var end = $('li[data-lesson_id="'+lesson_id+'"] .end').html();
            $.when( update(lesson_id, group_id, lessonhour_id, classroom_id, start, end) ).then(function() {
                moveLesson(lesson_id, lessonhour_id);
            });
        }
        else
            if( cloneLesson(lesson_id, lessonhour_id, dateView) ) {
                var end = changeAndFormatDate(dateView, -1);
                setTheEndDateOfTheLesson(lesson_id, end);
                moveLesson(lesson_id, lessonhour_id, dateView);
            }
        if(event.preventDefault) event.preventDefault();
        return false;
        
    });
    $('#groupPlan td').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function moveLesson(lesson_id, lessonhour_id, start='') {
    $('li[data-lesson_id="'+lesson_id+'"]').clone().appendTo('td[data-lessonhour_id="'+lessonhour_id+'"] ul').hide();
    if(start)  $('li[data-lesson_id="'+lesson_id+'"] .start').html(start);
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
        error: function() { alert('Błąd: lessonPlan/forGroup.js - funkcja update'); return false; }
    });
}

// wstawienie nowej lekcji (na podstawie lekcji przeciągniętej) od podanej daty
function cloneLesson(lesson_id, lessonhour_id, start) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/cloneLesson",
        data: { lesson_id: lesson_id, lesson_hour_id: lessonhour_id, start: start },
        success: function(result) { return result; },
        error: function(result) { alert('Błąd: groupPlan.js - funkcja cloneLesson'); alert(result); }
    });
    return true;
}

// zmiana daty o podaną liczbę dni oraz sformatowanie jej
function changeAndFormatDate(date, day) {
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

// ustawienie daty końcowej dla lekcji w planie
function setTheEndDateOfTheLesson(lesson_id, end) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/setTheEndDateOfTheLesson",
        data: { lesson_id: lesson_id, end: end },
        success: function() { return true; },
        error: function() { alert('Błąd: groupPlan.js - funkcja setTheEndDateOfTheLesson'); return false; }
    });
    return true;
}

// opuszczenie lekcji w polu całkowitego usunięcia
function dropLessonInCompleteRemoveField() {
    $('#completeRemove').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var id = data.getData('lesson_id');
        $.when( removeLesson(id) ).then(function() {
            $.when( $('li[data-lesson_id="'+id+'"]').fadeOut(1000) ).then(function() {
                $('li[data-lesson_id="'+id+'"]').remove();
            });
            $('#remainedHours').html( parseInt($('#remainedHours').html())+1 );
            $('#groupPlan button').fadeIn(1000);
            $('#groupHours').css('background', '#888');
        });
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#completeRemove').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

// usunięcie lekcji (całkowite usunięcie rekordu z bazy danych)
function removeLesson(lesson_id) {
    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji/"+lesson_id,
        success: function() { return true; },
        error: function(result) { alert('Błąd: groupPlan.js - funkcja removeLesson'); alert(result); }
    });
    return true;
}

// opuszczenie lekcji w polu całkowitego usunięcia
function dropLessonInTodayRemoveField() {
    $('#todayRemove').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var end = changeAndFormatDate($('#dateView').val(), -1);
        var lesson_id = data.getData('lesson_id');

        $('#remainedHours').html( $('#remainedHours').html()+2-1 );
        $('td.lesson button').fadeIn(1000);
        $('li[data-lesson_id="'+lesson_id+'"] .end').html(end);
        $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000);
        setTheEndDateOfTheLesson(lesson_id, end);
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#todayRemove').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
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
    tdLessonAddButtonClick();
    dragLesson();
    dropLessonInLessonPlan();
    dropLessonInCompleteRemoveField();
    dropLessonInTodayRemoveField();
    dateViewChange();
});