// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 29.08.2022 ------------------------ //
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

// --------------------------------- zarządzanie nauczycielami --------------------------------- //
function refreshRow(id, lp=0, version) {  // odświeżenie wiersza z klasą o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-teacher_id='+id+']').remove();
                $('tr[data-teacher_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            alert(id);
            alert(lp);
            alert(version);
            var error = '<tr><td class="error" colspan="12">Błąd odświeżania wiersza z nauczycielem.</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#teachers').animate({width: '1500px'}, 500);
        showCreateRow();
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/create",
        success: function(result) {
            $('table#teachers').append(result);
            $('input[name="degree"]').focus();
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania nauczyciela
    $('table#teachers').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('table#teachers').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie nauczyciela w bazie danych
    var degree      = $('#createRow input[name="degree"]').val();
    var first_name  = $('#createRow input[name="first_name"]').val();
    var last_name   = $('#createRow input[name="last_name"]').val();
    var family_name = $('#createRow input[name="family_name"]').val();
    var short       = $('#createRow input[name="short"]').val();
    var classroom_id= $('#createRow select[name="classroom_id"]').val();
    var firstYear   = $('#createRow select[name="first_year_id"]').val();
    var lastYear    = $('#createRow select[name="last_year_id"]').val();
    var order       = $('#createRow input[name="order"]').val();
    var lp = $('#countTeachers').val()-1+2;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel",
        data: { degree: degree, first_name: first_name, last_name: last_name, family_name: family_name, short: short, classroom_id: classroom_id, first_year_id: firstYear, last_year_id: lastYear, order: order },
        success: function(id) {
            refreshRow(id, lp, "add");
            $('#countTeachers').val(lp);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').after(error);
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
                $('tr[data-teacher_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd tworzenia wiersza z formularzem modyfikowania nauczyciela.</td></tr>';
                $('tr[data-teacher_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania nauczyciela
    $('.cancelUpdate').click(function() {
        var id = $(this).data('teacher_id');
        $('.editRow[data-teacher_id='+id+']').remove();
        $('tr[data-teacher_id='+id+']').show();
    });

    $('.update').click(function(){
        var id = $(this).data('teacher_id');
        update(id);
    });
}

function update(id) {   // zapisanie klasy w bazie danych
    var degree      = $('tr[data-teacher_id='+id+'] input[name="degree"]').val();
    var first_name  = $('tr[data-teacher_id='+id+'] input[name="first_name"]').val();
    var last_name   = $('tr[data-teacher_id='+id+'] input[name="last_name"]').val();
    var family_name = $('tr[data-teacher_id='+id+'] input[name="family_name"]').val();
    var short       = $('tr[data-teacher_id='+id+'] input[name="short"]').val();
    var classroom_id= $('tr[data-teacher_id='+id+'] select[name="classroom_id"]').val();
    var firstYear   = $('tr[data-teacher_id='+id+'] select[name="first_year_id"]').val();
    var lastYear    = $('tr[data-teacher_id='+id+'] select[name="last_year_id"]').val();
    var order       = $('tr[data-teacher_id='+id+'] input[name="order"]').val();
    var lp          = $('tr[data-teacher_id='+id+'] input[name="lp"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/"+id,
        data: { id: id, degree: degree, first_name: first_name, last_name: last_name, family_name: family_name, short: short, classroom_id: classroom_id, first_year_id: firstYear, last_year_id: lastYear, order: order },
        success: function() { refreshRow(id, lp); },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd modyfikowania nauczyciela.</td></tr>';
            $('tr[data-teacher_id='+id+'].editRow').after(error).hide();
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
            success: function() { $('tr[data-teacher_id='+id+']').remove(); },
            error: function() {
                var error = '<tr><td colspan="12" class="error">Błąd usuwania nauczyciela.</td></tr>';
                $('tr[data-teacher_id='+id+']').after(error).hide();
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