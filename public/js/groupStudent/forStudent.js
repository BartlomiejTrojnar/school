// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 12.09.2022 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania grup ucznia ----------------------- //

function showOrHideGroups() {
    var dateView = $('#dateView').val();
    var start, end;
    $('#studentGroups li').each(function() {    // sprawdzenie i wyświetlenie odpowiednich grup
        start = $(this).children('.dates').children('.start').html();
        if(start>dateView)  $(this).addClass('hide');
        end = $(this).children('.dates').children('.end').html();
        if(end<dateView)    $(this).addClass('hide');
        if(start<=dateView && end>=dateView)  $(this).removeClass('hide');
    });

    $('#studentGroupToWhichHeBelongedAtAnotherTime li').each(function() {
        start = $(this).children('.dates').children('.start').html();
        end   = $(this).children('.dates').children('.end').html();
        if(start<=dateView && end>=dateView)    $(this).addClass('hide');
        if(start>dateView || end<dateView)  $(this).removeClass('hide');
    });

    $('span.teacher').each(function() {     // sprawdzenie i wyświetlenie odpowiednich nauczycieli
        start = $(this).data('start');
        if(start>dateView)  $(this).addClass('hide');
        end = $(this).data('end');
        if(end<dateView)    $(this).addClass('hide');
        if(start<=dateView && end>=dateView)  $(this).removeClass('hide');
    });
}

function dateViewChange() {     // po zmianie widocznej na stronie daty widoku
    $('#dateView').bind('blur', function() {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rememberDates",
            data: { dateView: $('#dateView').val() },
            success: function()  {
                showOrHideGroups();
                refreshOtherGroupsInStudentsClass();
            },
        });
    });
}

// ------------------------ modyfikacja przynależności ucznia do grupy ------------------------- //
function refreshOtherGroupsInStudentsClass() {  // odświeżenie listy z innymi grupami w klasie ucznia
    var student = $('#student_id').val();
    var dateView = $('#dateView').val();
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/groupStudent/refreshOtherGroupsInStudentsClass",
        data: { student_id: student, dateView: dateView },
        success: function(result) {
            $('ul#otherGroupsInStudentsClass').replaceWith(result);
            otherGroupsInStudentsClass();
        },
        error: function() {
            var error = '<ul id="otherGroupsInStudentsClass"><li class="error">Błąd odświeżania innych grup w klasie ucznia.</li></ul>';
            $('ul#otherGroupsInStudentsClass').replaceWith(error);
        },
    });
}

function otherGroupsInStudentsClass() {  // kliknięcie w przycisk dodawania przy grupie
    $('#otherGroupsInStudentsClass').delegate('button.addGroup', 'click', function(){
        var group = $(this).data('group');
        var student = $('#student_id').val();
        var start = $('#dateView').val();
        var end = $('li[data-group="'+group+'"] time.end').html();
        var info = $('li[data-group="'+group+'"] aside').html();

        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie",
            data: { group_id: group, student_id: student, start: start, end: end },
            success: function(result) {
                var li = '<li data-student_group="'+result+'" class="">';
                li += '<button data-student_group="'+result+'" class="edit btn btn-primary"><i class="fa fa-edit"></i></button>';
                li += '<button data-student_group="'+result+'" class="destroy btn btn-primary"><i class="fa fa-remove"></i></button>';
                li += '<span class="dates"><span class="start">'+start+'</span> <i class="fas fa-stopwatch" style="font-size: 1.2em;"></i> <span class="end">'+end+'</span></span>';
                li += info + '</li>';
                $('#studentGroups').append(li);
                $('li[data-group="'+group+'"]').remove();
            },
            error: function() { 
                var error = '<li data-group="'+group+'" class="error">Błąd w czasie dodawania grupy dla ucznia</li>';
                $('li[data-group="'+group+'"]').replaceWith(error);
            }
        });
        return false;
    });
}

function editClick() {  // kliknięcie w przycisk modyfikowania przynależności do grupy
    $('#main-content').delegate('button.edit', 'click', function(e){
        var x = e.pageX;
        var y = e.pageY;
        var student_group = $(this).data('student_group');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie/"+student_group+"/edit",
            data: { student_group: student_group, version: "forStudent" },
            success: function(result) {
                $('#main-content').append(result);
                $('div.studentEditForm').css('top', y-150);
                $('div.studentEditForm').css('left', x-250);
                updateClick();
                dateStartChange();
                dateEndChange();
            },
            error: function() { 
                var error = '<li data-student_group="'+student_group+'" class="error">Błąd generowania formularza modyfikującego.</li>';
                $('li[data-student_group="'+student_group+'"]').replaceWith(error);
            }
        });
        return false;
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania grupy ucznia
    $('.cancelUpdate').click(function() {
        var student_group = $(this).data('group_student');
        $('div[data-student_group="'+student_group+'"]').remove();
    });

    $('.update').click(function(){
        var student_group = $(this).data('group_student');
        $.when( update(student_group) ).then(function() {
            $('div[data-student_group="'+student_group+'"]').remove();
        });
    });
}

function update(id) {   // zapisanie deklaracji w bazie danych
    var group_id   = $('div[data-student_group="'+id+'"] input[name="group_id"]').val();
    var student_id = $('div[data-student_group="'+id+'"] input[name="student_id"]').val();
    var start = $('div[data-student_group="'+id+'"] input[name="start"]').val();
    var end   = $('div[data-student_group="'+id+'"] input[name="end"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/grupa_uczniowie/"+id,
        data: { id: id, group_id: group_id, student_id: student_id, start: start, end: end },
        success: function() {
            $('li[data-student_group="'+id+'"] span.start').html(start);
            $('li[data-student_group="'+id+'"] span.end').html(end);
            showOrHideGroups();
        },
        error: function() {
            var error = '<li data-student_group="'+id+'" class="error">Błąd modyfikowania przynależności ucznia do grupy.</li>';
            $('li[data-student_group="'+id+'"]').replaceWith(error);
        },
    });
}

function destroyClick() {  // usunięcie przynależności ucznia do grupy (z bazy danych)
    $('.destroy').click(function() {
        var id = $(this).data('student_group');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa_uczniowie/" + id,
            success: function() {
                $.when( $('li[data-student_group="'+id+'"]').hide(1250) ).then(function() {
                    $('li[data-student_group="'+id+'"]').remove();
                });
                refreshOtherGroupsInStudentsClass();
            },
            error: function() {
                var error = '<li data-student_group="'+id+'" class="error">Błąd usuwania przynależności ucznia do grupy (możliwe że jest wpisana ocena końcowa).</li>';
                $('li[data-student_group="'+id+'"]').replaceWith(error);
            }
        });
        return false;
    });
}

// --------- ustawienie daty początku i końca grupy (po kliknięciu w propozycje daty) ---------- //
function proposedDateStartClick() {     // kliknięcie w proponowaną datę początkową dla ucznia w grupie (wpisanie daty w pole data_start)
    $('.proposedDateStart li').click(function() {
        var start = $(this).children('span').html();
        var end = $('input[name="end"]').val();
        $('input[name="start"]').val( start );
        datesValidate(start, end);
        return false;
    });
}

function proposedDateEndClick() {       // kliknięcie w proponowaną datę końcową dla ucznia w grupie (wpisanie daty w pole data_end)
    $('.proposedDateEnd li').click(function() {
        var start = $('input[name="start"]').val();
        var end = $(this).children('span').html();
        $('input[name="end"]').val( end );
        datesValidate(start, end);
        return false;
    });
}

function dateStartChange() {        // po zmianie daty początkowej - sprawdzenie dat grupy
    $('p.error').html('').addClass('hide');
    proposedDateStartClick();
    $('input[name="start"]').change(function() {
        start = $(this).val();
        end = $('input[name="end"]').val();
        datesValidate(start, end);
        return false;
    });
}
function dateEndChange() {          // po zmianie daty końcowej - sprawdzenie tej daty
    proposedDateEndClick();
    $('input[name="end"]').change(function() {
        var start = $('input[name="start"]').val();
        var end = $(this).val();
        datesValidate(start, end);
        return false;
    });
}

function datesValidate(start, end) {      // sprawdzenie czy daty są prawidłowe
    $('td.error').html('');
    $('button.update').removeClass('disabled');
    if( start == '' ) {
      $('td.error').html('data początkowa nie może być pusta');
      $('button.update').addClass('disabled');
    }
    if( end == '' ) {
      $('td.error').html('data końcowa nie może być pusta');
      $('button.update').addClass('disabled');
    }
    if( start > end ) {
      $('td.error').html('data początkowa nie może być późniejsza niż data końcowa');
      $('button.update').addClass('disabled');
    }
    if( start < $('#groupStart').html() ) {
      $('td.error').html('data początkowa nie może być wcześniejsza niż data początkowa grupy');
      $('button.update').addClass('disabled');
    }
    if( end > $('#groupEnd').html() ) {
      $('td.error').html('data końcowa nie może być późniejsza niż data końcowa grupy');
      $('button.update').addClass('disabled');
    }
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showOrHideGroups();
    editClick();
    destroyClick();
    otherGroupsInStudentsClass();
    dateViewChange();
});