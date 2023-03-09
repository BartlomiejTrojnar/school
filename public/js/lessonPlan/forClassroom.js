// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 24.02.2022 ------------------------ //
// ----------------- wydarzenia na stronie wyświetlania planu lekcji dla sali ------------------ //

// -------------- pokazanie aktualnych lekcji lub ukrycie lekcji z innych terminów ------------- //
function showOrHideLesson() {
    var dateView = $('#dateView').val();
    $('#classroomPlan li').each(function() {
        $(this).show();
        var start = $(this).children('.lessonDates').children('.start').html();
        if(start > dateView)    $(this).hide();
        var end = $(this).children('.lessonDates').children('.end').html();
        if(end < dateView)    $(this).hide();
        if(start <= dateView  &&  end >= dateView)  $('#remainedHours').html( $('#remainedHours').html()-1 );
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

function showStudentListForGroupClick() {  // kliknięcie pola z liczbą uczniów grupy
    $('.showStudentListForGroup').bind('click', function() {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/classroomPlan/showStudentListForGroup",
            data: { 
                group_id: $(this).data('group_id'),
                lesson_hour_id: $(this).data('lesson_hour_id'),
                dateView: $('#dateView').val(),
            },
            success: function(result) {
                $('body').append('<aside id="studentListForGroup">'+result+'</aside>');
            },
            error: function(error) { alert("B"+error); }
        });
        return false;
    });

    $('body').bind('click', function() {    // zamknięcie okna z listą uczniów po kliknięciu gdziekolwiek
        $('aside#studentListForGroup').hide();
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showOrHideLesson();
    dateViewChange();
    showStudentListForGroupClick();
});