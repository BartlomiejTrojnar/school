// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 21.11.2022 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania nauczycieli ---------------------- //
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

// ------------------------------------ zarządzanie nauczycielami ------------------------------------ //
function refreshRow(id, lp=0, add=false) {  // odświeżenie wiersza nauczyciela o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(add){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr[data-teacher_id='+id+']').replaceWith(result);
                $('tr[data-teacher_id='+id+']').hide().show(1250);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="12">Błąd odświeżania wiersza nauczyciela.</td></tr>';
            if(add) {
                $('tr.create').before(error);
            }
            else {
                $('tr[data-teacher_id='+id+']').before(error);
                $('td.error').hide().fadeIn(1250);
            }
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $('table#teachers').animate({width: '1250px'}, 500);
        $.when( $(this).hide(500) ).then(function() {
            showCreateRow();
        });
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/create",
        success: function(result) {
            $('table#teachers tr.create').before(result);
            $('#createRow').hide().fadeIn(1000);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').before(error);
            $('td.error').hide().show(1000);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania nauczyciela
    $('table#teachers').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').fadeOut(500) ).then(function() {
            $('#createRow').remove();
            $('#showCreateRow').fadeIn(1000);
        });
    });

    $('table#teachers').delegate('#add', 'click', function() {
        $.when( $('#createRow').fadeOut(500) ).then(function() {
            add();
            $('#createRow').remove();
            $('#showCreateRow').fadeIn(1000);
        });
    });
}

function add() {   // zapisanie nauczyciela w bazie danych
    var degree          = $('#createRow input[name="degree"]').val();
    var first_name      = $('#createRow input[name="first_name"]').val();
    var last_name       = $('#createRow input[name="last_name"]').val();
    var family_name     = $('#createRow input[name="family_name"]').val();
    var short           = $('#createRow input[name="short"]').val();
    var classroom_id    = $('#createRow select[name="classroom_id"]').val();
    var first_year_id   = $('#createRow select[name="first_year_id"]').val();
    var last_year_id    = $('#createRow select[name="last_year_id"]').val();
    var order           = $('#createRow input[name="order"]').val();
    var lp = parseInt($('#countTeachers').val())+1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel",
        data: { degree: degree, first_name: first_name, last_name: last_name, family_name: family_name, short: short,
            classroom_id: classroom_id, first_year_id: first_year_id, last_year_id: last_year_id, order: order },
        success: function(id) {
            refreshRow(id, lp, true);
            $('#countTeachers').val(lp);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').before(error);
            $('td.error').hide().fadeIn(1000);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania nauczyciela
    $('table#teachers').delegate('button.edit', 'click', function() {
        var id = $(this).data('teacher_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/nauczyciel/"+id+"/edit",
            data: { id: id, lp: lp },
            success: function(result) {
                $('tr[data-teacher_id='+id+']').before(result).fadeOut(1050);
                $('tr.editRow[data-teacher_id='+id+']').hide().fadeIn(1050);
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem modyfikowania.</td></tr>';
                $('tr[data-teacher_id='+id+']').before(error);
                $('td.error').hide().fadeIn(750);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania nauczyciela
    $('.cancelUpdate').click(function() {
        var id = $(this).data('teacher_id');
        $.when( $('.editRow[data-teacher_id='+id+']').fadeOut(500) ).then(function() {
            $('.editRow[data-teacher_id='+id+']').remove();
            $('tr[data-teacher_id='+id+']').hide().show(1250);
        });
    });

    $('.update').click(function(){
        var id = $(this).data('teacher_id');
        $.when( $('.editRow[data-teacher_id='+id+']').fadeOut(500) ).then(function() {
            update( id );
        });
    });
}

function update(id) {   // zapisanie nauczyciela w bazie danych
    var degree          = $('tr[data-teacher_id='+id+'] input[name="degree"]').val();
    var first_name      = $('tr[data-teacher_id='+id+'] input[name="first_name"]').val();
    var last_name       = $('tr[data-teacher_id='+id+'] input[name="last_name"]').val();
    var family_name     = $('tr[data-teacher_id='+id+'] input[name="family_name"]').val();
    var short           = $('tr[data-teacher_id='+id+'] input[name="short"]').val();
    var classroom_id    = $('tr[data-teacher_id='+id+'] select[name="classroom_id"]').val();
    var first_year_id   = $('tr[data-teacher_id='+id+'] select[name="first_year_id"]').val();
    var last_year_id    = $('tr[data-teacher_id='+id+'] select[name="last_year_id"]').val();
    var order           = $('tr[data-teacher_id='+id+'] input[name="order"]').val();
    var lp              = $('tr[data-teacher_id='+id+'] input[name="lp"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/"+id,
        data: { id: id, degree: degree, first_name: first_name, last_name: last_name, family_name: family_name, short: short,
            classroom_id: classroom_id, first_year_id: first_year_id, last_year_id: last_year_id, order: order },
        success: function() {
            $('tr[data-teacher_id='+id+'].editRow').remove();
            refreshRow(id, lp, false);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd modyfikowania nauczyciela.</td></tr>';
            $('tr[data-teacher_id='+id+'].editRow').before(error).remove();
            $('td.error').hide().show(750);
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
            success: function() { $('tr[data-teacher_id='+id+']').fadeOut(1050); },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd usuwania nauczyciela.</td></tr>';
                $('tr[data-teacher_id='+id+']').before(error);
                $('td.error').hide().fadeIn(1050);
            }
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    schoolYearChanged();

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});