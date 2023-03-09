// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 22.09.2022 ------------------------ //
// ------------------- wydarzenia na stronie wyświetlania planu lekcji grupy ------------------- //


// -------------- pokazanie aktualnych lekcji lub ukrycie lekcji z innych terminów ------------- //
function countStudents(dateView) {
    var countStudents = 0;
    $('#groupStudents li').each(function() {
        if( $(this).data('start')<=dateView && $(this).data('end')>=dateView ) countStudents++;
    });
    $('#groupInfo .studentsCount').html(countStudents);
}

function showOrHideTeachers(dateView) {
    $('#groupInfo .teacher').each(function() {
        if( $(this).data('start')>dateView || $(this).data('end')<dateView ) {
            $(this).hide();
        }
    });
}

function rewriteGroupInfo() {
    var dateView = $('#dateView').val();
    countStudents(dateView);
    showOrHideTeachers(dateView);
    $('#groupLessons .groupInfo').html( $('#groupInfo').html() );
    $('#groupLessons .teachers time').hide();
    $('div.groupInfo').html( $('#groupInfo').html() );
    $('div.groupInfo .teachers time').hide();
}

function moveLessonsToTable() {
    rewriteGroupInfo();
    var lesson_hour_id;
    $('#groupLessons li').each(function() {
        lesson_hour_id = $(this).data('lesson_hour_id');
        $('td[data-lesson_hour_id="'+lesson_hour_id+'"] ul').append($(this));
    });
}

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
        var lesson_hour_id = $(this).data('lesson_hour_id');
        var group_id = $('#group_id').val();
        var start =  $('#dateView').val();
        var end = $('#groupEnd').html();
        if( add(group_id, lesson_hour_id, start, end) )     setTimeout(function() {  
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
function add(group_id, lesson_hour_id, start, end) {
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji",
        data: { group_id: group_id, lesson_hour_id: lesson_hour_id, start: start, end: end },
        success: function(newID) {
            enterLessonToTable(newID, lesson_hour_id, start, end);
            return newID;
        },
        error: function(result) { alert('Błąd: teacherPlan.js - funkcja add'); alert(result); }
    });
    return true;
}

function enterLessonToTable(newID, lesson_hour_id, start, end) {
    var li = '<li data-lesson_id="'+newID+'" data-type="lesson" data-lesson_hour_id="'+lesson_hour_id+'"></li>';
    $('td[data-lesson_hour_id="'+lesson_hour_id+'"] ul').html( li );
    dragLesson();
    var lesson = '<span class="lessonDates" style="display: inline;"><time class="start">'+start+'</time> - <time class="end">'+end+'</time></span><br />';
    lesson += '<div class="groupInfo" style="display: inline;">groupInfo</div>';
    lesson += '<span class="classroom" style="display: inline;"><aside></aside></span>';
    $('li[data-lesson_id="'+newID+'"]').html( lesson );
    $('div.groupInfo').html( $('#groupInfo').html() );
    $('div.groupInfo .teachers time').hide();

}

// podnoszenie lekcji z planu lekcji
function dragLesson() {
    $('#groupPlan li').attr('draggable', 'true');
    $('#groupPlan').delegate('td.lesson li', 'dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('lesson_id', $(this).data('lesson_id'));
        data.setData('lesson_hour_id', $(this).parent().parent().data('lesson_hour_id'));
        return true;
    });
}

// opuszczenie lekcji na planie lekcji grupy
function dropLessonInLessonPlan() {
    $('#groupPlan td').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var lesson_hour_id = $(this).data('lesson_hour_id');
        var dateView = $('#dateView').val();
        var lesson_id = data.getData('lesson_id');
        var start = $('li[data-lesson_id="'+lesson_id+'"] .start').html();
        var old_lesson_hour_id = data.getData('lesson_hour_id');
        
        if(start == dateView) {
            var group_id = $('#group_id').val();
            var classroom_id = $('li[data-lesson_id="'+lesson_id+'"] .classroom aside').html();
            var end = $('li[data-lesson_id="'+lesson_id+'"] .end').html();
            $.when( update(lesson_id, group_id, lesson_hour_id, classroom_id, start, end) ).then(function() {
                moveLesson(lesson_id, lesson_hour_id, old_lesson_hour_id);
            });
        }
        else {
            var end = changeAndFormatDate(dateView, -1);
            $.when( setTheEndDateOfTheLesson(lesson_id, end) ).then(function() {
                cloneLesson(lesson_id, lesson_hour_id, dateView);
            });
        }
        if(event.preventDefault) event.preventDefault();
        return false;
        
    });
    $('#groupPlan td').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function moveLesson(lesson_id, lesson_hour_id, old_lesson_hour_id) {
    $('li[data-lesson_id="'+lesson_id+'"]').clone().appendTo('td[data-lesson_hour_id="'+lesson_hour_id+'"] ul').hide();
    $.when( $('td[data-lesson_hour_id="'+old_lesson_hour_id+'"] li[data-lesson_id="'+lesson_id+'"]').fadeOut(500) ).then(function() {
        $('td[data-lesson_hour_id="'+old_lesson_hour_id+'"] li[data-lesson_id="'+lesson_id+'"]').remove();
        $('li[data-lesson_id="'+lesson_id+'"]').fadeIn(500);
    });
}

function update(id, group_id, lesson_hour_id, classroom_id, start, end) {   // zapisanie zmian lekcji w bazie danych
    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji/"+id,
        data: { id: id, group_id: group_id, lesson_hour_id: lesson_hour_id, classroom_id: classroom_id, start: start, end: end },
        success: function(result) { return result; },
        error: function() { alert('Błąd: lessonPlan/forGroup.js - funkcja update'); return false; }
    });
}

// wstawienie nowej lekcji (na podstawie lekcji przeciągniętej) od podanej daty
function cloneLesson(lesson_id, lesson_hour_id, start) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/cloneLesson",
        data: { lesson_id: lesson_id, lesson_hour_id: lesson_hour_id, start: start },
        success: function(newID) {
            $('li[data-lesson_id="'+lesson_id+'"]').clone().appendTo('td[data-lesson_hour_id="'+lesson_hour_id+'"] ul');
            $('td[data-lesson_hour_id="'+lesson_hour_id+'"] li[data-lesson_id="'+lesson_id+'"]').attr('data-lesson_id', newID);
            $('li[data-lesson_id="'+newID+'"] .lessonDates .start').html(start);
            return newID;
        },
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
        success: function(wynik) {
            if(wynik) {
                $('li[data-lesson_id="'+lesson_id+'"] .lessonDates .end').html(end);
                $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000);    
            }
            return true;
        },
        error: function() { 
            alert('Błąd: groupPlan.js - funkcja setTheEndDateOfTheLesson'); return false; }
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
        $('li[data-lesson_id="'+lesson_id+'"] .lessonDates .end').html(end);
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
            success: function()  {
                rewriteGroupInfo();
                showOrHideLesson();
            },
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    moveLessonsToTable();
    showOrHideLesson();
    tdLessonAddButtonClick();
    dragLesson();
    dropLessonInLessonPlan();
    dropLessonInCompleteRemoveField();
    dropLessonInTodayRemoveField();
    dateViewChange();
});