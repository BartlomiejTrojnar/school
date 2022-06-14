// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 14.06.2022 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania deklaracji ----------------------- //

// ----------- pokazanie lub ukrycie grup, które mają już wszystkie lekcje na planie ----------- //
function countStudents(group, dateView) {
    var countStudents = 0;
    $('li[data-group_id='+group+'] .groupStudents li').each(function() {
        if( $(this).data('start')<=dateView && $(this).data('end')>=dateView ) countStudents++;
    });
    $('li[data-group_id='+group+'] .studentsCount').html(countStudents);
}

function showOrHideGroup() {
    var dateView = $('#dateView').val();
    var start, end;
    $('#gradeGroups li.group').each(function() {
        var hours = $(this).data('hours');
        $(this).children('.hours').children('var').html(hours);
        $(this).show();
        start = $(this).children('.groupDates').children('.start').html();
        end = $(this).children('.groupDates').children('.end').html();
        if(start > dateView)    $(this).hide();
        if(end   < dateView)    $(this).hide();
        $(this).children('span.teacher').each(function() {
           $(this).show();
           if($(this).data('start') > dateView)    $(this).hide();
           if($(this).data('end')   < dateView)    $(this).hide();
        });
        countStudents($(this).data('group_id'), dateView);
    });
}

// -------------- pokazanie aktualnych lekcji lub ukrycie lekcji z innych terminów ------------- //
function changeAndFormatDate(date, day) {       // zmiana daty o podaną liczbę dni oraz sformatowanie jej
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

function showOrHideLesson() {
    var dateView = $('#dateView').val();
    $('#gradePlan li').each(function() {
        $(this).show();
        var group_id = $(this).data('group_id');

        var start = $(this).children('.lessonDates').children('.start').html();
        if(start > dateView)    { $(this).hide(); return; }
        $(this).removeClass('bg-warning');
        $(this).children('.glyphicon-alert').remove();
        if(changeAndFormatDate(start, 7) >= dateView) {
            $(this).addClass('bg-warning');
            $(this).prepend('<span class="glyphicon glyphicon-alert"></span>');
        }
        var end = $(this).children('.lessonDates').children('.end').html();
        if(end < dateView)  { $(this).hide(); return; }

        $(this).children('span.teacher').each(function() {
            $(this).show();
            if($(this).data('start') > dateView)    $(this).hide();
            if($(this).data('end')   < dateView)    $(this).hide();
        });

        if(start <= dateView && end >= dateView) {
            var hours = parseInt( $('#gradeGroups li[data-group_id="'+group_id+'"] .hours var').html() ) - 1;
            $('#gradeGroups li[data-group_id="'+group_id+'"] .hours var').html(hours);
            if(hours<1) $('#gradeGroups li[data-group_id="'+group_id+'"]').fadeOut(1000);
        }
        var studentsCount = $('#gradeGroups li[data-group_id="'+group_id+'"] .studentsCount').html();
        $('#gradePlan li[data-group_id="'+group_id+'"] .studentsCount').html(studentsCount);
    });
}

function dateViewChange() {     // po zmianie widocznej na stronie daty widoku
    $('#dateView').bind('blur', function() {
        // zapamiętanie wybranej daty widoku
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rememberDates",
            data: { dateView: $('#dateView').val() },
            success: function()  {
                showOrHideGroup();
                showOrHideLesson();
            },
        });
    });
}

function dragGroup() {     // podnoszenie grupy klasy
    $('li.group').attr('draggable', 'true');
    $('li.group').bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('group_id', $(this).data('group_id'));
        data.setData('type', $(this).data('type'));
        return true;
    });
}

function dragLesson() {     // podnoszenie lekcji z planu lekcji
    $('#gradePlan li').attr('draggable', 'true');
    $('#gradePlan').delegate('li', 'dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('lesson_id', $(this).data('lesson_id'));
        data.setData('type', $(this).data('type'));
        data.setData('group_id', $(this).data('group_id'));
        data.setData('lessonhour_id', $(this).parent().parent().data('lessonhour_id'));
        return true;
    });
}

function decreaseVisibleGroupHours(group_id) {      // funkcja odczytuje i zmniejsza liczbę godzin do obsadzenia dla grupy
    var hours = $('li.group[data-group_id=' +group_id+ '] .hours var').html();
    hours = parseInt(hours) - 1;
    $('li.group[data-group_id=' +group_id+ '] .hours var').html(hours);
    if(hours<1) $('li.group[data-group_id=' +group_id+ ']').fadeOut(1500);
}
function increaseVisibleGroupHours(group_id) {      // funkcja odczytuje i zwiększa liczbę godzin do obsadzenia dla grupy
    var hours = $('li.group[data-group_id=' +group_id+ '] .hours var').html();
    hours = parseInt(hours) + 1;
    $('li.group[data-group_id=' +group_id+ '] .hours var').html(hours);
    if(hours>0) $('li.group[data-group_id=' +group_id+ ']').fadeIn(1500);
}

function addLessonToTable(id, group_id, lessonhour_id, start, end) {
    var lessonDescription = $('li[data-group_id="'+group_id+'"]').html();
    var li = '<li class="bg-warning" data-lesson_id="'+id+'" data-type="lesson" data-group_id="'+group_id+'">';
    li += lessonDescription + '</li>';
    $('td[data-lessonhour_id="'+lessonhour_id+'"] ul').append(li);
    $('li[data-lesson_id="'+id+'"] .start').html(start);
    $('li[data-lesson_id="'+id+'"] .end').html(end);
    $('li[data-lesson_id="'+id+'"] .groupDates').addClass('lessonDates').removeClass('groupDates');
    $('li[data-lesson_id="'+id+'"] .hours').remove();
    dragLesson();
}

function moveLesson(lesson_id, lessonhour_id, old_lessonhour_id, start='') {
    $('li[data-lesson_id="'+lesson_id+'"]').clone().appendTo('td[data-lessonhour_id="'+lessonhour_id+'"] ul').hide();
    if(start)  {
        $('td[data-lessonhour_id="'+lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"] .start').html(start);
        var end = changeAndFormatDate(start, -1);
        $('td[data-lessonhour_id="'+old_lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"] .end').html(end);
    }
    else {
        $.when( $('td[data-lessonhour_id="'+old_lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000) ).then(function() {
            $('td[data-lessonhour_id="'+old_lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"]').remove();
        });    
    }
    $.when( $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000) ).then(function() {
        $('td[data-lessonhour_id="'+lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"]').fadeIn(1000);
    });
}

function addLesson(group_id, lessonhour_id, start, end) {        // wstawienie nowej lekcji dla wskazanej grupy od podanej daty
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji",
        data: { group_id: group_id, lesson_hour_id: lessonhour_id, start: start, end: end },
        success: function(id) { addLessonToTable(id, group_id, lessonhour_id, start, end); },
        error: function() { alert('Błąd: gradePlan.js - funkcja addLesson'); return 0; }
    });
}

function setTheEndDateOfTheLesson(lesson_id, end) {     // ustawienie daty końcowej dla lekcji w planie
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/setTheEndDateOfTheLesson",
        data: { lesson_id: lesson_id, end: end },
        success: function() { return true; },
        error: function() { alert('Błąd: gradePlan.js - funkcja setTheEndDateOfTheLesson'); return false; }
    });
    return true;
}

function cloneLesson(lesson_id, lessonhour_id, old_lessonhour_id, start) {     // wstawienie nowej lekcji (na podstawie lekcji przeciągniętej) od podanej daty
    var end = $('li[data-lesson_id="'+lesson_id+'"] .end').html();
    setTheEndDateOfTheLesson(lesson_id, changeAndFormatDate(start, -1));
    moveLesson(lesson_id, lessonhour_id, old_lessonhour_id, start);

    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/lessonPlan/cloneLesson",
        data: { lesson_id: lesson_id, lesson_hour_id: lessonhour_id, start: start, end: end },
        success: function(result) { $('td[data-lessonhour_id="'+lessonhour_id+'"] li[data-lesson_id="'+lesson_id+'"]').attr('data-lesson_id', result).fadeIn(1000); },
        error: function() { alert('Błąd: gradePlan.js - funkcja cloneLesson'); return 0; }
    });
}

function changeLessonHour(lesson_id, lessonhour_id, old_lessonhour_id) {
    moveLesson(lesson_id, lessonhour_id, old_lessonhour_id);
    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji/"+lesson_id,
        data: { id: lesson_id, lesson_hour_id: lessonhour_id, classroom_id: 0 },
        error: function() { alert('Błąd: lessonPlan/forTeacher.js - funkcja changeLessonHour'); return false; }
    });
}

function dropInLessonPlan() {     // opuszczenie lekcji/grupy na planie lekcji klasy
    $('#gradePlan td').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var lessonhour_id = $(this).data('lessonhour_id');
        var dateView = $('#dateView').val();

        if( data.getData('type') == 'group' ) {
            var group_id = data.getData('group_id');
            var end = $("li[data-group_id="+group_id+"] .end").html();
            $("#schoolYearEnds li").each( function() {
                if( $(this).html()<dateView ) return;
                if( $(this).html()>end ) return;
                end = $(this).html();
            });
            decreaseVisibleGroupHours(group_id);  // zmniejszenie liczby lekcji do obsadzenia na liście grup
            // dodaj lekcję na wybranej godzinie od daty początkowej
            addLesson(group_id, lessonhour_id, dateView, end);
        }
        else {
            var lesson_id = data.getData('lesson_id')
            var start = $('li[data-lesson_id="'+lesson_id+'"] .start').html();
            var old_lessonhour_id = data.getData('lessonhour_id');

            if(start == dateView)   changeLessonHour(lesson_id, lessonhour_id, old_lessonhour_id);  // tylko zmiana godziny dla lekcji
            else    cloneLesson(lesson_id, lessonhour_id, old_lessonhour_id, dateView);    // sklonowanie lekcji z nową datą początkową i godziną lekcji, zmiana daty końcowej dla "starej" lekcji
        }
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#gradePlan td').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function removeLesson(lesson_id) {      // usunięcie lekcji (całkowite usunięcie rekordu z bazy danych)
    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/plan_lekcji/"+lesson_id,
        success: function() { return true; },
        error: function(result) { alert('Błąd: gradePlan.js - funkcja removeLesson'); alert(result); }
    });
    return true;
}

function dropLessonInCompleteRemoveField() {        // opuszczenie lekcji w polu całkowitego usunięcia
    $('#completeRemove').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        if( data.getData('type')!='lesson' ) {
            alert( 'Upuszczono coś innego niż lekcja!' );
            return false;
        }
        var lesson_id = data.getData('lesson_id');
        $.when( $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000) ).then(function() {
            $('li[data-lesson_id="'+lesson_id+'"]').remove();       // usunięcie lekcji z kodu HTML
        });
        increaseVisibleGroupHours( data.getData('group_id') );  // dodanie godziny do przypisania na planie lekcji na liście grup klasy
        removeLesson(lesson_id);
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#completeRemove').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function dropLessonInGradeGroupList() {     // opuszczenie lekcji w polu zawierającym grupy klasy
    $('#gradeGroups').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        if( data.getData('type') != 'lesson' ) {
            alert( 'Upuszczono coś innego niż lekcja!' );
            return false;
        }
        // dodaj godzinę na liście grup klasy (w górnej części strony)
        increaseVisibleGroupHours( data.getData('group_id') );
        var lesson_id = data.getData('lesson_id');
        var end = changeAndFormatDate($('#dateView').val(), -1);
        var start = $('li[data-lesson_id="'+lesson_id+'"] .start').html();

        $('li[data-lesson_id="'+lesson_id+'"] .end').html( end );     // zmiana daty końca lekcji na stronie
        $.when( $('li[data-lesson_id="'+lesson_id+'"]').fadeOut(1000) ).then( function() {
            if(start>end) {
                $('li[data-lesson_id="'+lesson_id+'"]').remove();   // usunięcie lekcji z kodu HTML
                removeLesson(lesson_id);                            // usunięcie lekcji z bazy danych
            }
            else    setTheEndDateOfTheLesson(lesson_id, end);       // ustawienie daty końca dla lekcji
        });

        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#gradeGroups').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showOrHideGroup();
    showOrHideLesson();
    dateViewChange();
    dragGroup();
    dragLesson();
    dropInLessonPlan();
    dropLessonInCompleteRemoveField();
    dropLessonInGradeGroupList();
});