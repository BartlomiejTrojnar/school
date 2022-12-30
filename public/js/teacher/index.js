// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.12.2022 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania nauczycieli ---------------------- //
var fadeOutTime = 575, fadeInTime = 1275;

function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

function refreshRow(id, lp, add="true") {  // odświeżenie wiersza z nauczycielem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(add) {
                $('tr.create').before(result);
                $('tr[data-stadium_id="'+id+'"]').fadeIn(fadeInTime);
                $('#showCreateRow').show();
            }
            else {
                $.when( $('tr[data-teacher_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
                    $('tr[data-teacher_id="'+id+'"]').replaceWith(result);
                    $('tr[data-teacher_id="'+id+'"]').hide().fadeIn(fadeInTime);    
                });
            }
        },
        error: function() {
            var error = '<tr data-teacher_id="'+id+'"><td class="error" colspan="12">Błąd odświeżania wiersza z nauczycielem.</td></tr>';
            if(add) $('tr.create').before(error);
            else $('tr[data-teacher_id="'+id+'"]').replaceWith(error);
            $('td.error').hide().fadeIn(fadeInTime);
        },
    });
}

// --------------------------------- zarządzanie nauczycielami --------------------------------- //
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $('table#teachers').animate({width: '95%'}, 500);
        $.when( $(this).fadeOut(fadeOutTime) ).then(function() { showCreateRow(); });
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/create",
        success: function(result) {
            $('table#teachers tr.create').before(result);
            $('#createRow').hide().fadeIn(fadeInTime);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').after(error);
            $('td.error').hide().fadeIn(fadeInTime);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania nauczyciela
    $('table#teachers').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').fadeOut(fadeOutTime) ).then(function() {
            $('#createRow').remove();
            $('#showCreateRow').fadeIn(fadeInTime);
        });
    });

    $('table#teachers').delegate('#add', 'click', function() {
        $.when( $('#createRow').fadeOut(fadeOutTime) ).then(function() {
            $('#showCreateRow').fadeIn(fadeInTime);
            add();
        });
    });
}

function add() {   // zapisanie nauczyciela w bazie danych
    var first_name      = $('#createRow input[name="first_name"]').val();
    var last_name       = $('#createRow input[name="last_name"]').val();
    var family_name     = $('#createRow input[name="family_name"]').val();
    var short           = $('#createRow input[name="short"]').val();
    var degree          = $('#createRow input[name="degree"]').val();
    var classroom_id    = $('#createRow select[name="classroom_id"]').val();
    var first_year_id   = $('#createRow select[name="first_year_id"]').val();
    var last_year_id    = $('#createRow select[name="last_year_id"]').val();
    var order           = $('#createRow input[name="order"]').val();
    var lp = parseInt( $('#countTeachers').val() ) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel",
        data: { first_name: first_name, last_name: last_name, family_name: family_name, short: short, degree: degree,
            classroom_id: classroom_id, first_year_id: first_year_id, last_year_id: last_year_id, order: order },
        success: function(id) {
            refreshRow(id, lp);
            $('#countTeachers').html(lp);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').before(error);
            $('td.error').hide().fadeIn(fadeInTime);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania nauczyciela
    $('#teachers').delegate('button.edit', 'click', function() {
        var id = $(this).data('teacher_id');
        var lp = $(this).parent().parent().children(":first").html();

        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/nauczyciel/"+id+"/edit",
            data: { id: id },
            success: function(result) {
                $.when( $('tr[data-teacher_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
                    $('tr[data-teacher_id="'+id+'"]').replaceWith(result);
                    $('tr.editRow[data-teacher_id="'+id+'"]').hide().fadeIn(fadeInTime);
                    updateClick(lp);
                });
            },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Nie mogę utworzyć formularza do zmiany danych.</td></tr>';
                $('tr[data-teacher_id="'+id+'"]').before(error);
                $('td.error').hide().fadeIn(fadeInTime);
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania nauczyciela
    $('.cancelUpdate').click(function() {
        var id = $(this).data('teacher_id');
        $.when( $('.editRow[data-teacher_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
            refreshRow(id, lp, false);
        });
    });

    $('.update').click(function(){
        var id = $(this).data('teacher_id');
        $.when( $('.editRow[data-teacher_id='+id+']').fadeOut(fadeOutTime) ).then( function() {
            update(id, lp);
        });
    });
}

function update(id, lp) {   // zapisanie zmian w bazie danych
    var first_name      = $('tr[data-teacher_id="'+id+'"] input[name="first_name"]').val();
    var last_name       = $('tr[data-teacher_id="'+id+'"] input[name="last_name"]').val();
    var family_name     = $('tr[data-teacher_id="'+id+'"] input[name="family_name"]').val();
    var short           = $('tr[data-teacher_id="'+id+'"] input[name="short"]').val();
    var degree          = $('tr[data-teacher_id="'+id+'"] input[name="degree"]').val();
    var classroom_id    = $('tr[data-teacher_id="'+id+'"] select[name="classroom_id"]').val();
    var first_year_id   = $('tr[data-teacher_id="'+id+'"] select[name="first_year_id"]').val();
    var last_year_id    = $('tr[data-teacher_id="'+id+'"] select[name="last_year_id"]').val();
    var order           = $('tr[data-teacher_id="'+id+'"] input[name="order"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/"+id,
        data: { id: id, first_name: first_name, last_name: last_name, family_name: family_name, short: short, degree: degree,
            classroom_id: classroom_id, first_year_id: first_year_id, last_year_id: last_year_id, order: order },
        success: function() {  refreshRow(id, lp, false); },
        error: function() {
            var error = '<tr data-teacher_id="'+id+'"><td colspan="12" class="error">Błąd modyfikowania nauczyciela.</td></tr>';
            $('tr[data-teacher_id='+id+']').replaceWith(error);
            $('td.error').hide().fadeIn(fadeInTime);
        },
    });
}

function destroyClick() {  // usunięcie nauczyciela (z bazy danych)
    $('table#teachers').delegate('button.destroy', 'click', function() {
        var id = $(this).data('teacher_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/nauczyciel/" + id,
            success: function() {
                $.when( $('tr[data-teacher_id='+id+']').fadeOut(fadeOutTime) ).then( function() {
                    $('tr[data-teacher_id='+id+']').remove();
                 });
            },
            error: function() {
                var error = '<tr data-teacher_id="'+id+'"><td colspan="12" class="error">Błąd usuwania nauczyciela.</td></tr>';
                $('tr[data-teacher_id='+id+']').replaceWith(error);
                $('td.error').hide().fadeIn(fadeInTime);
            }
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    if ( $( "#jumpToThePage" ).length ) location.href = $('#jumpToThePage').attr('href');

    schoolYearChanged();
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});