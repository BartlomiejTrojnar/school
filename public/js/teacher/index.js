// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.09.2021 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania nauczycieli ---------------------- //
/*
function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rok_szkolny/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

function schoolChanged() {  // wybór szkoły w polu select
    $('select[name="school_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/szkola/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}


// ------------------------------------ zarządzanie nauczycielami ------------------------------------ //
function refreshRow(id, lp=0, version) {  // odświeżenie wiersza z klasą o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasa/refreshRow",
        data: { id: id, version: "forIndex", lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-grade_id='+id+']').remove();
                $('tr[data-grade_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="4">Błąd odświeżania wiersza z klasą.</td></tr>';
            $('tr.create').before(error);
        },
    });
}
*/

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
        url: "http://localhost/school/public/nauczyciel/create",
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

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania klasy
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

function add() {   // zapisanie klasy w bazie danych
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
            alert(id);
            refreshRow(id, lp, "add");
            $('#countTeachers').val(lp);
        },
        error: function() {
            var error = '<tr><td colspan="12" class="error">Błąd dodawania nauczyciela.</td></tr>';
            $('table#teachers tr.create').before(error);
            $('td.error').hide().fadeIn(1000);
        },
    });
}
/*
function editClick() {     // kliknięcie przycisku modyfikowania klasy
    $('table#grades').delegate('button.edit', 'click', function() {
        var id = $(this).data('grade_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasa/"+id+"/edit",
            data: { id: id, lp: lp, version: "forIndex" },
            success: function(result) {
                $('tr[data-grade_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="4" class="error">Błąd tworzenia wiersza z formularzem modyfikowania klasy.</td></tr>';
                $('tr[data-grade_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania klasy
    $('.cancelUpdate').click(function() {
        var id = $(this).data('grade_id');
        $('.editRow[data-grade_id='+id+']').remove();
        $('tr[data-grade_id='+id+']').show();
        return false;
    });

    $('.update').click(function(){
        var id = $(this).data('grade_id');
        update(id);
        return false;
    });
}

function update(id) {   // zapisanie deklaracji w bazie danych
    var year_of_beginning   = $('tr[data-grade_id='+id+'] input[name="year_of_beginning"]').val();
    var year_of_graduation  = $('tr[data-grade_id='+id+'] input[name="year_of_graduation"]').val();
    var symbol              = $('tr[data-grade_id='+id+'] input[name="symbol"]').val();
    var school_id           = $('tr[data-grade_id='+id+'] select[name="school_id"]').val();
    var lp                  = $('tr[data-grade_id='+id+'] input[name="lp"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasa/"+id,
        data: { id: id, year_of_beginning: year_of_beginning, year_of_graduation: year_of_graduation, symbol: symbol, school_id: school_id },
        success: function() { refreshRow(id, lp, "forIndex"); },
        error: function() {
            var error = '<tr><td colspan="4" class="error">Błąd modyfikowania klasy.</td></tr>';
            $('tr[data-grade_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie deklaracji (z bazy danych)
    $('table#grades').delegate('button.destroy', 'click', function() {
        var id = $(this).data('grade_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasa/" + id,
            success: function() { $('tr[data-grade_id='+id+']').remove(); },
            error: function() {
                var error = '<tr><td colspan="4" class="error">Błąd usuwania klasy.</td></tr>';
                $('tr[data-grade_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}
*/

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    //if ( $( "#jumpToThePage" ).length ) location.href = $('#jumpToThePage').attr('href');

    //schoolYearChanged();
    //schoolChanged();

    showCreateRowClick();
    addClick();
    //editClick();
    //destroyClick();
});