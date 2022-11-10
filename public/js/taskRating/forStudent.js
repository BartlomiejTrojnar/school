// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) kwiecień 2021 -------------------- //
// ---------------------- wydarzenia na stronie wyświetlania oceny zadań ----------------------- //
/*
// ----------------------------- zarządzanie ocenami zadań ucznia ------------------------------ //
function refreshTaskRatingsTable(student_id) {  // odświeżenie tabeli z ocenami zadań dla ucznia
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania/refreshTable",
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
*/
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
        url: "http://localhost/school/ocena_zadania/create",
        data: { student_id: student_id, version: "forStudent" },
        success: function(result) { $('table#taskRatings tr.create').after(result); addClick(); },
        error: function() {
            var error = '<tr><td colspan="13" class="error">Błąd tworzenia wiersza z formularzem dodawania oceny zadania.</td></tr>';
            $('table#taskRatings tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania oceny zadania
    $('table#taskRatings').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(750) ).then(function() {
            $('#createRow').remove();
            $('#showCreateRow').show(750);    
        });
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
    var student_id  = $('#createRow input[name="student_id"]').val();
    var task_id     = $('#createRow select[name="task_id"]').val();
    var deadline    = $('#createRow input[name="deadline"]').val();
    var implementation_date = $('#createRow input[name="implementation_date"]').val();
    var version     = $('#createRow input[name="version"]').val();
    var importance  = $('#createRow input[name="importance"]').val();
    var rating_date = $('#createRow input[name="rating_date"]').val();
    var points      = $('#createRow input[name="points"]').val();
    var rating      = $('#createRow input[name="rating"]').val();
    var comments    = $('#createRow input[name="comments"]').val();
    var diary       = $('#createRow input[name="diary"]').val();
    var entry_date  = $('#createRow input[name="entry_date"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania",
        data: { student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date, version: version,
            importance: importance, rating_date: rating_date, points: points, rating: rating, comments: comments, diary: diary, entry_date: entry_date },
        success: function(newID) {  
            addRowToTable(newID, task_id, deadline, implementation_date, version, importance, rating_date, points, rating, comments, diary, entry_date);
        },
        error: function() {
            var error = '<tr><td colspan="13" class="error">Błąd dodawania oceny zadania dla ucznia!</td></tr>';
            $('table#taskRatings tr.create').after(error);
        },
    });
}

function addRowToTable(newID, task_id, deadline, implementation_date, version, importance, rating_date, points, rating, comments, diary, entry_date) {
    var lp = parseInt( $("#lp").val() )+1;
    $("#lp").val(lp);
    var newRow = '<tr data-task-rating-id="'+newID+'"><td class="lp">'+lp+'</td>';
    newRow += '<td>'+task_id+'</td>';
    newRow += '<td>'+deadline+'</td>';
    newRow += '<td>'+implementation_date+'</td>';
    newRow += '<td>'+version+'</td>';
    newRow += '<td>'+importance+'</td>';
    newRow += '<td>'+rating_date+'</td>';
    newRow += '<td>'+points+'</td>';
    newRow += '<td>'+rating+'</td>';
    newRow += '<td>'+comments+'</td>';
    newRow += '<td>'+diary+'</td>';
    newRow += '<td>'+entry_date+'</td>';
    newRow += '</tr>';
    $('tr.create').after(newRow);
    //alert(newID); refreshTaskRatingsTable(student_id);
}

function editClick() {     // kliknięcie przycisku modyfikowania oceny zadania
    $('#taskRatings').delegate('button.edit', 'click', function() {
        var id = $(this).data('task_rating_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ocena_zadania/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) {
                $.when( $('tr[data-task_rating_id="'+id+'"]').hide(500) ).then(  function() {
                    $('tr[data-task_rating_id="'+id+'"]').before(result);
                    $('tr.editRow[data-task_rating_id="'+id+'"]').hide().show(1500);
                    updateClick();
                });
            },
            error: function() {
                var error = '<tr><td class="error" colspan="13">Błąd tworzenia wiersza z formularzem dodawania oceny zadania.</td></tr>';
                $('tr[data-task_rating_id="'+id+'"]').before(error);
                $('td.error').hide().show(1000);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania oceny zadania
    $('.cancelUpdate').click(function() {
        var id = $(this).attr('data-task_rating_id');
        $.when( $('tr.editRow[data-task_rating_id='+id+']').hide(500) ).then(  function() {
            $('tr.editRow[data-task_rating_id='+id+']').remove();
            $('tr[data-task_rating_id='+id+']').show(1500);
        });
    });

    $('.update').click(function(){
        var id = $(this).attr('data-task_rating_id');
        $.when( $('tr.editRow[data-task_rating_id='+id+']').hide(500) ).then(  function() {
            $('tr.editRow[data-task_rating_id='+id+']').remove();
            $('tr[data-task_rating_id='+id+']').show(1500);
            update();
        });
    });
}


/*
function destroyClick() {  // usunięcie oceny zadania ucznia (z bazy danych)
    $('.destroy').click(function() {
        var id = $(this).data('taskratingid');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ocena_zadania/" + id,
            success: function() {
                $.when( $('tr[data-task-rating-id="'+id+'"]').hide(1250) ).then(function() {
                    $('li[data-task-rating-id="'+id+'"]').remove();
                });
            },
            error: function() {
                var error = '<tr data-task-rating-id="'+id+'"><td colspan="13" class="error">Błąd przy usuwaniu oceny zadania.</td></tr>';
                $('tr[data-task-rating-id="'+id+'"]').replaceWith(error);
            }
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
        url: "http://localhost/school/ocena_zadania/writeInTheDiary/"+ task_rating_id,
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
        url: "http://localhost/school/ocena_zadania/removeFromDiary/"+ task_rating_id,
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
    //var student_id = $('input#student_id').val();
    //refreshTaskRatingsTable(student_id);
    showCreateRowClick();
    editClick();
    //destroyClick();
    //gradeChanged();
    //groupChanged();
    //diaryYesNoChanged();
    //buttonDiaryClick();
});