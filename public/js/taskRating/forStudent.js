// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 12.11.2022 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania oceny zadań ----------------------- //

// ----------------------------- zarządzanie ocenami zadań ucznia ------------------------------ //
function refreshRow(id, lp, add=false) {  // odświeżenie wiersza z oceną zadania
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(add) {
                $('tr.create').before(result);
                $('tr[data-task_rating_id='+id+']').hide().show(1000);
            }
            else $('tr[data-task_rating_id='+id+']').replaceWith(result);
        },
        error: function() {
            var error = '<tr><td class="error" colspan="13">Błąd odświeżania wiersza z oceną zadania dla ucznia.</td></tr>';
            if(add) {
                $('tr.create').before(error);
                $('td.error').hide().show(1000);
            }
            else $('tr[data-task_rating_id='+id+']').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#taskRatings').animate({width: '100%'}, 500);
        showCreateRow( $('input#student_id').val() );
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
    var diary       = $('#createRow input[name="diary"]').is(':checked');
    if(diary)   diary = 1;  else diary = 0;
    var entry_date  = $('#createRow input[name="entry_date"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania",
        data: { student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date, version: version,
            importance: importance, rating_date: rating_date, points: points, rating: rating, comments: comments, diary: diary, entry_date: entry_date },
        success: function(newID) {  
            var lp = parseInt( $("#lp").val() )+1;
            $("#lp").val(lp);
            refreshRow(newID, lp, true);
        },
        error: function() {
            var error = '<tr><td colspan="13" class="error">Błąd dodawania oceny zadania dla ucznia!</td></tr>';
            $('table#taskRatings tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania oceny zadania
    $('#taskRatings').delegate('button.edit', 'click', function() {
        var id = $(this).data('task_rating_id');
        var lp = $(this).parent().parent().children(':first').children().html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ocena_zadania/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) {
                $.when( $('tr[data-task_rating_id="'+id+'"]').hide(500) ).then(  function() {
                    $('tr[data-task_rating_id="'+id+'"]').before(result);
                    $('tr.editRow[data-task_rating_id="'+id+'"]').hide().show(1500);
                    updateClick(lp);
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

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania oceny zadania
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
            $('tr[data-task_rating_id='+id+']').show(1500);
            update(id, lp);
        });
    });
}

function update(id, lp) {   // zapisanie oceny zadania w bazie danych
    var student_id  = $('tr[data-task_rating_id='+id+'] input[name="student_id"]').val();
    var task_id     = $('tr[data-task_rating_id='+id+'] select[name="task_id"]').val();
    var deadline    = $('tr[data-task_rating_id='+id+'] input[name="deadline"]').val();
    var implementation_date = $('tr[data-task_rating_id='+id+'] input[name="implementation_date"]').val();
    var version     = $('tr[data-task_rating_id='+id+'] input[name="version"]').val();
    var importance  = $('tr[data-task_rating_id='+id+'] input[name="importance"]').val();
    var rating_date = $('tr[data-task_rating_id='+id+'] input[name="rating_date"]').val();
    var points      = $('tr[data-task_rating_id='+id+'] input[name="points"]').val();
    var rating      = $('tr[data-task_rating_id='+id+'] input[name="rating"]').val();
    var comments    = $('tr[data-task_rating_id='+id+'] input[name="comments"]').val();
    var diary       = $('tr[data-task_rating_id='+id+'] input[name="diary"]').val();
    var entry_date  = $('tr[data-task_rating_id='+id+'] input[name="entry_date"]').val();
    $('tr.editRow[data-task_rating_id='+id+']').remove();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania/"+id,
        data: { id: id, student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date,
            version: version, importance: importance, rating_date: rating_date, points: points, rating: rating, comments: comments,
            diary: diary, entry_date: entry_date },
        success: function() {   refreshRow(id, lp); },
        error: function() {
            var error = '<tr><td class="error" colspan="13">Błąd! Nie można zapisać zmian.</td></tr>';
            $('tr[data-task_rating_id="'+id+'"]').before(error);
            $('td.error').hide().show(1000);
        },
    });
}

function destroyClick() {  // usunięcie oceny zadania ucznia (z bazy danych)
    $('#taskRatings').delegate('button.destroy', 'click', function() {
        var id = $(this).data('task_rating_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ocena_zadania/" + id,
            success: function() {
                $.when( $('tr[data-task_rating_id="'+id+'"]').hide(750) ).then(function() {
                    $('tr[data-task_rating_id="'+id+'"]').remove();
                });
            },
            error: function() {
                var error = '<tr data-task_rating_id="'+id+'"><td colspan="13" class="error">Błąd przy usuwaniu oceny zadania.</td></tr>';
                $('tr[data-task_rating_id="'+id+'"]').before(error);
                $('td.error').hide().show(500);
            }
        });
        return false;
    });
}

///////////////////////////////////////////////////////////////////////////////////////////////////
function buttonDiaryClick() {   // kliknięcie przycisku z informacją o wpisie w dzienniku - wpisanie lub usunięcie daty wpisu do dziennika
    $('#taskRatings').delegate('.no-diary', 'click', function() {
        writeInTheDiary( $(this).data('task_rating_id') );
    });

    $('#taskRatings').delegate('.entry-diary', 'click', function() {
        removeFromDiary( $(this).data('task_rating_id') );
    });
}

function writeInTheDiary(task_rating_id) {  // wpisanie informacji że ocena jest wpisana do dziennika (wraz z datą wpisu)
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania/writeInTheDiary/"+ task_rating_id,
        success: function(result) {
            $('tr[data-task_rating_id='+task_rating_id+'] td.entry_date').html(result);
            var button = '<button class="btn-warning entry-diary" data-task_rating_id="'+task_rating_id+'"><i class="fas fa-circle"></i></button>';
            $('tr[data-task_rating_id='+task_rating_id+'] td.diary').html(button);
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
        success: function() {
            $('tr[data-task_rating_id='+task_rating_id+'] td.entry_date').html('');
            var button = '<button class="btn-warning no-diary" data-task_rating_id="'+task_rating_id+'"><i class="far fa-circle"></i></button>';
            $('tr[data-task_rating_id='+task_rating_id+'] td.diary').html(button);
        },
        error: function(result) { alert('Błąd w funkcji removeFromDiary: '+result); },
    });
    return false;
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowClick();
    editClick();
    destroyClick();
    buttonDiaryClick();
});