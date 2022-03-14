// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) styczeń 2021 --------------------- //
// --------------------- wydarzenia na stronie do importowania ocen zadań ---------------------- //

function buttonImportClick() {  // po kliknięciu przycisku typu Import
    $('button.import').bind('click', function(){
        // pobranie wszystkich danych z formularza
        var student_id = $(this).parent().children('div').children('input[name="student_id"]').val();
        var task_id = $(this).parent().children('div').children('input[name="task_id"]').val();
        var deadline = $(this).parent().children('div').children('input[name="deadline"]').val();
        var implementation_date = $(this).parent().children('div').children('input[name="implementation_date"]').val();
        var comments = $(this).parent().children('div').children('input[name="comments"]').val();
        var version = $(this).parent().children('div').children('input[name="version"]').val();
        var importance = $(this).parent().children('div').children('input[name="importance"]').val();
        var points = $(this).parent().children('div').children('input[name="points"]').val();
        var rating = $(this).parent().children('div').children('input[name="rating"]').val();
        var rating_date = $(this).parent().children('div').children('input[name="rating_date"]').val();
        var diary = $(this).parent().children('div').children('input[name="diary"]').val();
        var entry_date = $(this).parent().children('div').children('input[name="entry_date"]').val();
        var importId = $(this).data('import-id');

        // wysłanie danych w celu stworzenia nowego rekordu
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/taskRatingImport/store/",
            data: { student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date, comments: comments, version: version,
                importance: importance, points: points, rating: rating, rating_date: rating_date, diary: diary, entry_date: entry_date },
            success: function(result) {
                if(result==1)   $('tr[data-import-id="'+importId+'"]').remove();
                return false;
            },
            error: function(result) { alert(result); },
        });
        return false;
    });
}

function buttonUpdateClick() {  // po kliknięciu przycisku typu Update
    $('button.update').bind('click', function(){
        // pobranie wszystkich danych z formularza
        var id = $(this).parent().children('div').children('input[name="id"]').val();
        var student_id = $(this).parent().children('div').children('input[name="student_id"]').val();
        var task_id = $(this).parent().children('div').children('input[name="task_id"]').val();
        var deadline = $(this).parent().children('div').children('input[name="deadline"]').val();
        var implementation_date = $(this).parent().children('div').children('input[name="implementation_date"]').val();
        var comments = $(this).parent().children('div').children('input[name="comments"]').val();
        var version = $(this).parent().children('div').children('input[name="version"]').val();
        var importance = $(this).parent().children('div').children('input[name="importance"]').val();
        var points = $(this).parent().children('div').children('input[name="points"]').val();
        var rating = $(this).parent().children('div').children('input[name="rating"]').val();
        var rating_date = $(this).parent().children('div').children('input[name="rating_date"]').val();
        var diary = $(this).parent().children('div').children('input[name="diary"]').val();
        var entry_date = $(this).parent().children('div').children('input[name="entry_date"]').val();
        var importId = $(this).data('import-id');

        // wysłanie danych w celu stworzenia nowego rekordu
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/taskRatingImport/update/",
            data: { id: id, student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date, comments: comments, version: version,
                importance: importance, points: points, rating: rating, rating_date: rating_date, diary: diary, entry_date: entry_date },
            success: function(result) {
                if(result==1)   $('tr[data-import-id="'+importId+'"]').remove();
            },
            error: function(result) { alert(result); },
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    buttonImportClick();
    buttonUpdateClick();
});