// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) kwiecień 2021 -------------------- //
// ---------------------- wydarzenia na stronie wyświetlania oceny zadań ----------------------- //

// ----------------------------- zarządzanie ocenami zadań ucznia ------------------------------ //
function refreshTaskRatingsTable(student_id) {  // odświeżenie tabeli z ocenami zadań dla ucznia
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ocena_zadania/refreshTable",
        data: { student_id: student_id, version: "forStudent" },
        success: function(result) {
            $('section#taskRatingsTable').replaceWith(result);
            showCreateRowClick();
            addClick();
            //editClick();
            //destroyClick();
        },
        error: function() {
            var error = '<p class="error">Błąd odświeżania tabeli z ocenami zadań dla ucznia: Dokończyć funkcję odświeżającą tabelę z ocenami zadań dla ucznia.</p>';
            $('table#taskRatings').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#taskRatings').animate({width: '100%'}, 500);
        var student_id = $('input#student_id').val();
        showCreateRow(student_id);
        return false;
    });
}

function showCreateRow(student_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ocena_zadania/create",
        data: { student_id: student_id, version: "forStudent" },
        success: function(result) { $('table#taskRatings tr.create').after(result); addClick(); },
        error: function() {
            var error = '<tr><td colspan="13"><p class="error">Błąd tworzenia wiersza z formularzem dodawania oceny zadania.</p></td></tr>';
            $('table#taskRatings tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania oceny zadania
    $('table#taskRatings').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });

    $('table#taskRatings').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });
}

function add() {   // zapisanie oceny zadania w bazie danych
    var student_id = $('#createRow input[name="student_id"]').val();
    var task_id = $('#createRow select[name="task_id"]').val();
    var deadline = $('#createRow input[name="deadline"]').val();
    var implementation_date = $('#createRow input[name="implementation_date"]').val();
    var version = $('#createRow input[name="version"]').val();
    var importance = $('#createRow input[name="importance"]').val();
    var rating_date = $('#createRow input[name="rating_date"]').val();
    var points = $('#createRow input[name="points"]').val();
    var rating = $('#createRow input[name="rating"]').val();
    var comments = $('#createRow input[name="comments"]').val();
    var diary = $('#createRow input[name="diary"]').val();
    var entry_date = $('#createRow input[name="entry_date"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/ocena_zadania",
        data: { student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date, version: version, importance: importance,
            rating_date: rating_date, points: points, rating: rating, comments: comments, diary: diary, entry_date: entry_date },
        success: function(comment) {  refreshTaskRatingsTable(student_id);  },
        error: function() {
            var error = '<tr><td colspan="13"><p class="error">Błąd dodawania oceny zadania dla ucznia</p></td></tr>';
            $('table#taskRatings tr.create').after(error);
        },
    });
}


/*
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
*/
// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    var student_id = $('input#student_id').val();
    refreshTaskRatingsTable(student_id);
    showCreateRowClick();
    //gradeChanged();
    //groupChanged();
    //diaryYesNoChanged();
    //buttonDiaryClick();
});