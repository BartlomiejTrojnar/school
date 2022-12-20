// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 20.12.2022 ------------------------ //
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

function refreshRow(id, lp, version) {  // odświeżenie wiersza z nauczycielem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/refreshRow",
        data: { id: id, version: "forIndex", lp: lp },
        success: function(result) {
            if(version=="add")  $('tr.create').before(result);
            else    $('tr[data-teacher_id='+id+']').replaceWith(result);
            $('tr[data-teacher_id="'+id+'"]').hide().fadeIn(750);
        },
        error: function() {
            var error = '<tr data-teacher_id="'+id+'"><td class="error" colspan="12">Błąd odświeżania wiersza nauczyciela.</td></tr>';
            if(version=="add") {
                $('tr.create').before(error);
                $('td.error').hide().fadeIn(1275);
            }
            else {
                $('tr[data-teacher_id="'+id+'"]').replaceWith(error);
                $('tr[data-teacher_id="'+id+'"]').hide().fadeIn(750);
            }
        },
    });
}


// ------------------------------------ zarządzanie nauczycielami ------------------------------------ //
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $('table#teachers').animate({width: '95%'}, 500);
        $.when($(this).fadeOut(1000)).then(function() {
            showCreateRow();
        });
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/create",
        data: { version: "forIndex" },
        success: function(result) { $('table#teachers tr.create').before(result); },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').after(error);
            $('td.error').hide().fadeIn(1000);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania nauczyciela
    $('table#teachers').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').fadeOut(750) ).then(function() {
            $('#createRow').remove();
            $('#showCreateRow').fadeIn(750);
        });
    });

    $('table#teachers').delegate('#add', 'click', function() {
        $.when( $('#createRow').fadeOut(750) ).then(function() {
            $('#showCreateRow').fadeIn(750);
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
    var order           = $('#createRow input[name="order"]').val();
    var classroom_id    = $('#createRow select[name="classroom_id"]').val();
    var first_year_id   = $('#createRow select[name="first_year_id"]').val();
    var last_year_id    = $('#createRow select[name="last_year_id"]').val();
    var lp = parseInt($('#countTeachers').val()) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel",
        data: { first_name: first_name, last_name: last_name, family_name: family_name, short: short, degree: degree,
            order: order, classroom_id: classroom_id, first_year_id: first_year_id, last_year_id },
        success: function(id) {
            refreshRow(id, lp, "add");
            $('#countTeachers').val(lp);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').before(error);
            $('tr.error').hide().fadeIn(750);
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
            data: { id: id, lp: lp, version: "forIndex" },
            success: function(result) {
                $.when($('tr[data-teacher_id='+id+']').fadeOut(750) ).then(function() {
                    $('tr[data-teacher_id='+id+']').before(result);
                    $('tr.editRow').fadeIn(750);
                    updateClick();
                });
            },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem modyfikowania nauczyciela.</td></tr>';
                $('tr[data-teacher_id='+id+']').before(error);
                $('td.error').hide().fadeIn(1750);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania nauczyciela
    $('.cancelUpdate').click(function() {
        var id = $(this).data('teacher_id');
        $.when( $('.editRow[data-teacher_id='+id+']').fadeOut(750) ).then(function() {
            $('.editRow[data-teacher_id='+id+']').remove();
            $('tr[data-teacher_id='+id+']').fadeIn(750);
        });
    });

    $('.update').click(function(){
        var id = $(this).data('teacher_id');
        $.when( $('.editRow[data-teacher_id='+id+']').fadeOut(750) ).then(function() {
            update(id);
        });
    });
}

function update(id) {   // zapisanie w bazie danych zmienionych danych nauczyciela
    var first_name      = $('tr[data-teacher_id='+id+'] input[name="first_name"]').val();
    var last_name       = $('tr[data-teacher_id='+id+'] input[name="last_name"]').val();
    var family_name     = $('tr[data-teacher_id='+id+'] input[name="family_name"]').val();
    var short           = $('tr[data-teacher_id='+id+'] input[name="short"]').val();
    var degree          = $('tr[data-teacher_id='+id+'] input[name="degree"]').val();
    var order           = $('tr[data-teacher_id='+id+'] input[name="order"]').val();
    var classroom_id    = $('tr[data-teacher_id='+id+'] select[name="classroom_id"]').val();
    var first_year_id   = $('tr[data-teacher_id='+id+'] select[name="first_year_id"]').val();
    var last_year_id    = $('tr[data-teacher_id='+id+'] select[name="last_year_id"]').val();
    var lp              = $('tr[data-teacher_id='+id+'] input[name="lp"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/"+id,
        data: { id: id, first_name: first_name, last_name: last_name, family_name: family_name, short: short, degree: degree,
            order: order, classroom_id: classroom_id, first_year_id: first_year_id, last_year_id },
        success: function() {
            $('.editRow[data-teacher_id='+id+']').remove();
            refreshRow(id, lp, "forIndex");
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd modyfikowania nauczyciela.</td></tr>';
            $('.editRow[data-teacher_id='+id+']').before(error).remove();
            $('td.error').hide().fadeIn(1750);
            $('[data-teacher_id='+id+']').fadeIn(750);
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
                $.when( $('tr[data-teacher_id='+id+']').fadeOut(1275) ).then(function() {
                    $('tr[data-teacher_id='+id+']').remove();
                    $('#countTeachers').val(  $('#countTeachers').val()-1  );
                });
            },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd usuwania nauczyciela.</td></tr>';
                $('tr[data-teacher_id='+id+']').before(error);
                $('td.error').hide().fadeIn(1275);
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