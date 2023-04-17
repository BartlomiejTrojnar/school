// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 17.04.2023 ------------------------ //
// --------------------- wydarzenia na stronie wyświetlania grup dla klasy --------------------- //
// ------------------------------ wybór klasy w polu select ------------------------------------ //
function gradeChanged() {
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/klasa/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór przedmiotu w polu select ------------------------------- //
function subjectChanged() {
    $('select[name="subject_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}
// --------------------------- wybór poziomu egzaminu w polu select ---------------------------- //
function levelChanged() {
    $('select[name="level"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/level/change/" + $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór nauczyciela w polu select ------------------------------ //
function teacherChanged() {
    $('select[name="teacher_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/nauczyciel/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { alert('Błąd przy zmianie nauczyciela'); window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór nauczyciela w polu select ------------------------------ //
function schoolYearChanged() {
    $('select[name="schoolYear"]').bind('change', function(){
        var year = 1900 + parseInt($(this).val());
        start = (year-1)+'-09-01';
        end = year+'-08-31';
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() { $('#start').val(start); $('#end').val(end); rememberDates(start, end); },
            error: function() { alert('Błąd przy zmianie roku szkolnego'); window.location.reload(); },
        });
        return false;
    });
}

// po zmianie daty początkowej - sprawdzenie dat grupy
function dateStartChange() {
    $('input[name="start"]').bind('blur', function(){
        start = $(this).val();
        end = $('input[name="end"]').val();
        rememberDates(start, end);
        return false;
    });
}
// po zmianie daty końcowej - sprawdzenie dat grupy
function dateEndChange() {
    $('input[name="end"]').bind('blur', function(){
        start = $('input[name="start"]').val();
        end = $(this).val();
        rememberDates(start, end);
        return false;
    });
}
//zapamiętanie dat w sesji
function rememberDates(start, end) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rememberDates",
        data: { dateView: start, end: end },
        success: function() { window.location.reload(); },
        error: function() { 
            alert('Błąd');
            //window.location.reload();
         },
    });
}

// ------------------------------ zarządzanie grupami w klasie ------------------------------- //
function refreshRow(id, version, type, lp=0) {  // odświeżenie wiersza z grupą o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/grupa/refreshRow",
        data: { id: id, version: version, lp: lp },
        success: function(result) {
            if(type=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $.when( $('tr.editRow[data-group_id='+id+']').hide(750) ).then(function() {
                    $('tr.editRow[data-group_id='+id+']').remove();
                    $('tr[data-group_id='+id+']').replaceWith(result);
                    $('tr[data-group_id='+id+']').show(1000);
                });
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="12">Błąd odświeżania wiersza grupy!</td></tr>';
            $('tr[data-group_id='+id+']').replaceWith(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania grupy
    $('#groups').delegate('button.edit', 'click', function() {
        var id = $(this).data('group_id');
        var lp = $(this).parent().parent().children(":first").children().html();
        var version = $(this).data('version');

        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa/"+id+"/edit",
            data: { id: id, version: version },
            success: function(result) {
                $.when( $('tr[data-group_id='+id+']').hide(500) ).then(function() {
                    $('tr[data-group_id='+id+']').before(result);
                    $('tr.editRow[data-group_id='+id+']').show(500);
                    updateClick(lp);
                });
            },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Nie mogę utworzyć formularza do zmiany danych.</td></tr>';
                $('tr[data-group_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania grupy
    $('.cancelUpdate').click(function() {
        var id = $(this).data('group_id');
        $.when( $('.editRow[data-group_id='+id+']').hide(750) ).then(function() {
            $('.editRow[data-group_id='+id+']').remove();
            $('tr[data-group_id='+id+']').show(750);
        });
    });

    $('.update').click(function(){
        var version = $(this).data('version');
        update( $(this).attr('data-group_id'), version, lp );
    });
}

function update(id, version, lp) {   // zapisanie grupy w bazie danych
    var subject_id  = $('tr[data-group_id='+id+'] select[name="subject_id"]').val();
    var level       = $('tr[data-group_id='+id+'] select[name="level"]').val();
    var comments    = $('tr[data-group_id='+id+'] input[name="comments"]').val();
    var start       = $('tr[data-group_id='+id+'] input[name="start"]').val();
    var end         = $('tr[data-group_id='+id+'] input[name="end"]').val();
    var hours       = $('tr[data-group_id='+id+'] input[name="hours"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/grupa/"+id,
        data: { id: id, subject_id: subject_id, level: level, comments: comments, start: start, end: end, hours: hours },
        success: function() { refreshRow(id, version, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Nie można zmienić danych grupy.</td></tr>';
            $('tr[data-group_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie grupy (z bazy danych)
    $('#groups').delegate('.destroy', 'click', function() {
        var id = $(this).data('group_id');
            $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/grupa/" + id,
            success: function() {  
                $.when( $('tr[data-group_id='+id+']').hide(1500) ).then(function() {
                    $('tr[data-group_id='+id+']').remove();
            });  },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd usuwania grupy.</td></tr>';
                $('tr[data-group_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChanged();
    subjectChanged();
    levelChanged();
    teacherChanged();
    schoolYearChanged();
    dateStartChange();
    dateEndChange();

    editClick();
    destroyClick();
});