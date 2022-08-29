// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 02.07.2022 ------------------------ //
// -------------------------- wydarzenia na stronie wyświetlania klas -------------------------- //
function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="school_year_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
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


// ------------------------------------ zarządzanie klasami ------------------------------------ //
function refreshRow(id, lp=0, version) {  // odświeżenie wiersza z klasą o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/klasa/refreshRow",
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
        $('table#grades').animate({width: '1000px'}, 500);
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/klasa/create",
        data: { version: "forIndex" },
        success: function(result) { $('table#grades').append(result); },
        error: function() {
            var error = '<tr><td colspan="4" class="error">Błąd tworzenia wiersza z formularzem dodawania klasy.</td></tr>';
            $('table#grades tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania klasy
    $('table#grades').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('table#grades').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie klasy w bazie danych
    var year_of_beginning = $('#createRow input[name="year_of_beginning"]').val();
    var year_of_graduation = $('#createRow input[name="year_of_graduation"]').val();
    var symbol = $('#createRow input[name="symbol"]').val();
    var school_id = $('#createRow select[name="school_id"]').val();
    var lp = $('#countGrades').val()-1+2;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/klasa",
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
            url: "http://localhost/school/klasa/"+id+"/edit",
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
    });

    $('.update').click(function(){
        var id = $(this).data('grade_id');
        update(id);
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
        url: "http://localhost/school/klasa/"+id,
        data: { id: id, year_of_beginning: year_of_beginning, year_of_graduation: year_of_graduation, symbol: symbol, school_id: school_id },
        success: function() { refreshRow(id, lp, "forIndex"); },
        error: function() {
            var error = '<tr><td colspan="4" class="error">Błąd modyfikowania klasy.</td></tr>';
            $('tr[data-grade_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie klasy (z bazy danych)
    $('table#grades').delegate('button.destroy', 'click', function() {
        var id = $(this).data('grade_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/klasa/" + id,
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
    if ( $( "#jumpToThePage" ).length ) location.href = $('#jumpToThePage').attr('href');

    schoolYearChanged();
    schoolChanged();

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});