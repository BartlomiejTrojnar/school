// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 02.07.2022 ------------------------ //
// --------------------- wydarzenia na stronie wyświetlania lat szkolnych ---------------------- //

// ------------------------------- zarządzanie latami szkolnymi -------------------------------- //
function refreshRow(id, version="add", lp=0) {  // odświeżenie wiersza z rokiem szkolnym o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rok_szkolny/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
            }
            else {
                $('tr.editRow[data-school_year_id='+id+']').remove();
                $('tr[data-school_year_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="8">Błąd odświeżania wiersza z rokiem szkolnym.</td></tr>';
            if(version=="add")  $('tr.create').before(error);
            else $('tr[data-school_year_id='+id+']').replaceWith(error);
        },
    });
}
 
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#schoolYears').animate({width: '100%'}, 500);
        showCreateRow();
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/rok_szkolny/create",
        data: { version: "forIndex" },
        success: function(result) { $('table#schoolYears').append(result); },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem dodawania roku_szkolnego.</td></tr>';
            $('table#schoolYears tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania roku szkolnego
    $('table#schoolYears').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('table#schoolYears').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie roku szkolnego w bazie danych
    var dateStart = $('#createRow input[name="dateStart"]').val();
    var dateEnd = $('#createRow input[name="dateEnd"]').val();
    var date_of_classification_of_the_last_grade = $('#createRow input[name="date_of_classification_of_the_last_grade"]').val();
    var date_of_graduation_of_the_last_grade = $('#createRow input[name="date_of_graduation_of_the_last_grade"]').val();
    var date_of_classification = $('#createRow input[name="date_of_classification"]').val();
    var date_of_graduation = $('#createRow input[name="date_of_graduation"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rok_szkolny",
        data: { date_start: dateStart, date_end: dateEnd, date_of_classification_of_the_last_grade: date_of_classification_of_the_last_grade,
                date_of_graduation_of_the_last_grade: date_of_graduation_of_the_last_grade, date_of_classification: date_of_classification, date_of_graduation: date_of_graduation },
        success: function(id) { refreshRow(id, "add"); },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Błąd dodawania roku szkolnego.</td></tr>';
            $('table#schoolYears tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania roku szkolnego
    $('table#schoolYears').delegate('button.edit', 'click', function() {
        var id = $(this).data('school_year_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/"+id+"/edit",
            data: { id: id, lp: lp },
            success: function(result) {
                $('tr[data-school_year_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem modyfikowania roku szkolnego.</td></tr>';
                $('tr[data-school_year_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania roku szkolnego
    $('.cancelUpdate').click(function() {
        var id = $(this).data('school_year_id');
        $('.editRow[data-school_year_id='+id+']').remove();
        $('tr[data-school_year_id='+id+']').show();
    });

    $('.update').click(function(){
        var id = $(this).data('school_year_id');
        update(id);
        $('.editRow[data-school_year_id='+id+']').remove();
        $('tr[data-school_year_id='+id+']').show();
    });
}

function update(id) {   // zapisanie roku szkolnego w bazie danych
    var dateStart                                   = $('tr[data-school_year_id='+id+'] input[name="dateStart"]').val();
    var dateEnd                                     = $('tr[data-school_year_id='+id+'] input[name="dateEnd"]').val();
    var date_of_classification_of_the_last_grade    = $('tr[data-school_year_id='+id+'] input[name="date_of_classification_of_the_last_grade"]').val();
    var date_of_graduation_of_the_last_grade        = $('tr[data-school_year_id='+id+'] input[name="date_of_graduation_of_the_last_grade"]').val();
    var date_of_classification                      = $('tr[data-school_year_id='+id+'] input[name="date_of_classification"]').val();
    var date_of_graduation                          = $('tr[data-school_year_id='+id+'] input[name="date_of_graduation"]').val();
    var lp = $('tr[data-school_year_id='+id+'] input[name="lp"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rok_szkolny/"+id,
        data: { id: id, date_start: dateStart, date_end: dateEnd, date_of_classification_of_the_last_grade: date_of_classification_of_the_last_grade,
            date_of_graduation_of_the_last_grade: date_of_graduation_of_the_last_grade, date_of_classification: date_of_classification, date_of_graduation: date_of_graduation },
        success: function() { refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Błąd modyfikowania roku_szkolnego.</td></tr>';
            $('tr[data-school_year_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie roku szkolnego (z bazy danych)
    $('table#schoolYears').delegate('button.destroy', 'click', function() {
        var id = $(this).data('school_year_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/" + id,
            success: function() { $('tr[data-school_year_id='+id+']').remove(); },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Błąd usuwania roku szkolnego.</td></tr>';
                $('tr[data-school_year_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    //if ( $( "#jumpToThePage" ).length ) location.href = $('#jumpToThePage').attr('href');
//
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});