// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.09.2021 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania nauczycieli ---------------------- //

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

// --------------------------------- zarządzanie nauczycielami --------------------------------- //
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
        //return false;
    });

    $('table#teachers').delegate('#add', 'click', function() {
        alert('Dokończ funkcję dodawania nauczyciela - skrypt js/teacher/index.js');
        //add();
        $('#createRow').remove();
        $('#showCreateRow').show();
        //return false;
    });
}



// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    schoolYearChanged();

    showCreateRowClick();
    addClick();
    // editClick();
    // destroyClick();
});