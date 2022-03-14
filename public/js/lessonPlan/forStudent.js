// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 25.02.2022 ------------------------ //
// ------------------------ wydarzenia na stronie wyświetlania uczniów ------------------------- //

function showOrHideLesson() {
    var dateView = $('#dateView').val();
    $('#studentPlan div.lesson').each(function() {
        $(this).show();
        if($(this).data('start') > dateView)    $(this).hide();
        if($(this).data('end') < dateView)      $(this).hide();

        $(this).children('span.teacher').each(function() {
            $(this).show();
            if($(this).data('start') > dateView)    $(this).hide();
            if($(this).data('end') < dateView)      $(this).hide();
        });
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
            success: function()  { showOrHideLesson(); },
        });
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showOrHideLesson();
    dateViewChange();
});