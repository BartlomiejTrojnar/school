// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) grudzień 2020 -------------------- //
// ----------------------- wydarzenia na stronie wyświetlania oceny zadań ----------------------- //

function gradeChanged() {  // wybór klasy w polu select
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasa/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}

function groupChanged() {  // wybór grupy w polu select
    $('select[name="group_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/grupa/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { alert('Błąd: '+result); window.location.reload(); },
        });
        return false;
    });
}

function diaryYesNoChanged() {  // wybór tak/nie w polu select dotyczącego wpisu oceny do dziennika
    $('select[name="diaryYesNo"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/diaryYesNo/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}


///////////////////////////////////////////////////////////////////////////////////////////////////
function buttonDiaryClick() {   // kliknięcie przycisku z informacją o wpisie w dzienniku - wpisanie lub usunięcie daty wpisu do dziennika
    $('#taskRatings').delegate('.no-diary', 'click', function() {
        var task_rating_id = $(this).data('task_rating_id');
        writeInTheDiary(task_rating_id);
        return false;
    });
    $('#taskRatings').delegate('.entry-diary', 'click', function() {
        var task_rating_id = $(this).data('task_rating_id');
        removeFromDiary(task_rating_id);
        return false;
    });
}

function writeInTheDiary(task_rating_id) {  // wpisanie informacji że ocena jest wpisana do dziennika (wraz z datą wpisu)
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ocena_zadania/writeInTheDiary/"+ task_rating_id,
        success: function(result) {
            $('tr[data-task-rating-id='+task_rating_id+'] td.entry_date').html(result);
            var button = '<button class="btn-warning entry-diary" data-task_rating_id="'+task_rating_id+'"><i class="fas fa-circle"></i></button>';
            $('tr[data-task-rating-id='+task_rating_id+'] td.diary').html(button);
        },
        error: function(result) { alert('Błąd w funkcji writeInTheDiary: '+result); },
    });
    return false;
}

function removeFromDiary(task_rating_id) {  // wpisanie informacji, że ocena nie jest wpisana do dziennika (oraz wyczyszczenie daty wpisu)
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ocena_zadania/removeFromDiary/"+ task_rating_id,
        success: function(result) {
            $('tr[data-task-rating-id='+task_rating_id+'] td.entry_date').html('');
            var button = '<button class="btn-warning no-diary" data-task_rating_id="'+task_rating_id+'"><i class="far fa-circle"></i></button>';
            $('tr[data-task-rating-id='+task_rating_id+'] td.diary').html(button);
        },
        error: function(result) { alert('Błąd w funkcji removeFromDiary: '+result); },
    });
    return false;
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChanged();
    groupChanged();
    diaryYesNoChanged();
    buttonDiaryClick();
});