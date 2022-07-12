// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 12.07.2022 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania przedmiotów ----------------------- //

// --------------------------------- zarządzanie przedmiotami ---------------------------------- //
function refreshRow(id, lp, version="edit") {  // odświeżenie wiersza z przedmiotem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
                $('#countSubjects').html(lp);
            }
            else {
                $('tr.editRow[data-subject_id='+id+']').remove();
                $('tr[data-subject_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="7">Błąd odświeżania wiersza z przedmiotem.</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {     // kliknięcie w przycisk dodawania - pokazanie wiersza dodawania przemiotu
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('table#subjects').animate({width: '1000px'}, 500);
        showCreateRow();
    });
}

function showCreateRow() {      // załadowanie wiersza dodawania przedmiotu
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot/create",
        success: function(result) {
            $('table#subjects').append(result);
            $('input[name="name"]').focus();
        },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem dodawania przedmiotu.</td></tr>';
            $('table#subjects tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania przedmiotu
    $('table#subjects').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('table#subjects').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie przedmiotu w bazie danych
    var name        = $('#createRow input[name="name"]').val();
    var short_name  = $('#createRow input[name="short_name"]').val();
    var actual      = $('#createRow input[name="actual"]').prop('checked');
    var order_in_the_sheet = $('#createRow input[name="order_in_the_sheet"]').val();
    var expanded = $('#createRow input[name="expanded"]').prop('checked');
    var lp = parseInt( $('#countSubjects').html() );
    
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot",
        data: { name: name, short_name: short_name, actual: actual, order_in_the_sheet: order_in_the_sheet, expanded: expanded },
        success: function(id) { refreshRow(id, lp+1, "add"); },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd dodawania przedmiotu.</td></tr>';
            $('table#subjects tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania przedmiotu
    $('table#subjects').delegate('button.edit', 'click', function() {
        var id = $(this).data('subject_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/"+id+"/edit",
            data: { id: id, lp: lp },
            success: function(result) {
                $('tr[data-subject_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem modyfikowania przedmiotu.</td></tr>';
                $('tr[data-subject_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania przedmiotu
    $('.cancelUpdate').click(function() {
        var id = $(this).data('subject_id');
        $('.editRow[data-subject_id='+id+']').remove();
        $('tr[data-subject_id='+id+']').show();
    });

    $('.update').click(function(){
        var id = $(this).data('subject_id');
        update(id);
    });
}

function update(id) {   // zapisanie przedmiotu w bazie danych
    var name                = $('tr[data-subject_id='+id+'] input[name="name"]').val();
    var short_name          = $('tr[data-subject_id='+id+'] input[name="short_name"]').val();
    var actual              = $('tr[data-subject_id='+id+'] input[name="actual"]').prop('checked');
    var order_in_the_sheet  = $('tr[data-subject_id='+id+'] input[name="order_in_the_sheet"]').val();
    var expanded            = $('tr[data-subject_id='+id+'] input[name="expanded"]').prop('checked');
    var lp                  = parseInt( $('tr[data-subject_id='+id+'] var.lp').html() );

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot/"+id,
        data: { id: id, name: name, short_name: short_name, actual: actual, order_in_the_sheet: order_in_the_sheet, expanded: expanded },
        success: function() { refreshRow(id, lp); },
        error: function() {
            var error = '<tr><td colspan="7" class="error">Błąd modyfikowania przedmiotu.</td></tr>';
            $('tr[data-subject_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie przedmiotu (z bazy danych)
    $('table#subjects').delegate('button.destroy', 'click', function() {
        var id = $(this).data('subject_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/" + id,
            success: function() { $('tr[data-subject_id='+id+']').remove(); },
            error: function() {
                var error = '<tr><td colspan="7" class="error">Błąd usuwania przedmiotu.</td></tr>';
                $('tr[data-subject_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    //if ( $( "#jumpToThePage" ).length ) location.href = $('#jumpToThePage').attr('href');

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});