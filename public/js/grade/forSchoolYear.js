// -------------------- (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 -------------------- //
// ----------------- wydarzenia na stronie wyświetlania klas dla roku szkolnego ----------------- //
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

// ------------------------------------ zarządzanie klasami ------------------------------------ //
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

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#grades').animate({width: '900px'}, 500);
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    var school_year_id = $('#school_year_id').val();
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasa/create",
        data: { version: "forSchoolYear", school_year_id: school_year_id },
        success: function(result) { $('table#grades').append(result); },
        error: function() {
            var error = '<tr><td colspan="3" class="error">Błąd tworzenia wiersza z formularzem dodawania klasy.</td></tr>';
            $('table#grades tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania klasy
    $('table#grades').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });

    $('table#grades').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
        return false;
    });
}

function add() {   // zapisanie klasy w bazie danych
    var year_of_beginning   = $('#createRow input[name="year_of_beginning"]').val();
    var year_of_graduation  = $('#createRow input[name="year_of_graduation"]').val();
    var symbol              = $('#createRow input[name="symbol"]').val();
    var school_id           = $('#createRow select[name="school_id"]').val();
    var lp = $('#countGrades').val()-1+2;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasa",
        data: { year_of_beginning: year_of_beginning, year_of_graduation: year_of_graduation, symbol: symbol, school_id: school_id },
        success: function(id) {  
            refreshRow(id, lp, "add");
            $('#countGrades').val(lp);
        },
        error: function() {
            var error = '<tr><td colspan="4" class="error">Błąd dodawania klasy.</td></tr>';
            $('table#grades tr.create').after(error);
        },
    });
}

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
                $('table#grades').animate({width: '750px'}, 500);
                $('tr[data-grade_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="4" class="error">Błąd tworzenia wiersza z formularzem modyfikowania klasy.</td></tr>';
                $('tr[data-grade_id='+id+']').after(error);
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

function update(id) {   // zapisanie klasy w bazie danych
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
            $('tr.editRow[data-grade_id='+id+']').after(error).hide();
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

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    schoolChanged();
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});