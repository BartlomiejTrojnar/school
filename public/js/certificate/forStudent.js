// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 06.11.2022 ------------------------ //
// ------------------- wydarzenia na stronie wyświetlania świadectw ucznia --------------------- //

// ------------------------------ zarządzanie świadectwa ucznia ------------------------------ //
function refreshRow(id, lp=995, add=0) {  // odświeżenie wiersza ze świadectwem
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/certificate/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(add){
                $('#createRow').before(result);
                $('[data-certificate_id="'+id+'"]').hide().show(750);
                $('[name="lp"]').val( $('[name="lp"]').val()-1+2 );
            }
            else {
                $('tr[data-certificate_id="'+id+'"]').replaceWith(result);
                $('tr[data-certificate_id="'+id+'"]').hide().show(500);
            }   
        },
        error: function() {
            var error = '<tr><td class="error" colspan="6">Błąd! Nie mogę załadować świadectwa.</td></tr>';
            $('tr[data-certificate_id="'+id+'"]').before(error);
            $('td.error').hide().show(500);
            
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/certificate/create",
            data: { version: "forStudent" },
            success: function(result) {
                $('#certificatesTable .footer').before(result);
                $('#createRow').hide();
                $.when( $('#showCreateRow').hide(500) ).then(  function() {
                    $('#createRow').show(500);
                });
            },
            error: function() {
                var error = '<tr><td class="error" colspan="6">Błąd tworzenia wiersza z formularzem dodawania świadectwa.</td></tr>';
                $('#certificatesTable .footer').before(error);
                $('#certificatesTable .error').hide().show(500);
            },
        });
        return false;
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania świadectwa
    $('#certificatesTable').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(500) ).then(  function() {
            $('#showCreateRow').show(500);
            $('#createRow').remove();
         });
    });

    $('#certificatesTable').delegate('#add', 'click', function() {
        add();
        $.when( $('#createRow').hide(500) ).then(  function() {
           $('#showCreateRow').show(500);
           $('#createRow').remove();
        });
    });
}

function add() {   // zapisanie świadectwa w bazie danych
    var student_id      = $('#createRow input[name="student_id"]').val();
    var type            = $('#createRow select[name="type"]').val();
    var templates_id    = $('#createRow select[name="templates_id"]').val();
    var council_date    = $('#createRow input[name="council_date"]').val();
    var date_of_issue   = $('#createRow input[name="date_of_issue"]').val();
    var lp = $('[name="lp"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/certificate",
        data: { student_id: student_id, type: type, templates_id: templates_id, council_date: council_date, date_of_issue: date_of_issue },
        success: function(id) {  refreshRow(id, lp, 1);  },
        error: function() {
            var error = '<tr><td class="error" colspan="6">Błąd tworzenia wiersza z formularzem dodawania świadectwa.</td></tr>';
            $('#certificatesTable .footer').before(error);
            $('#certificatesTable .error').hide().show(1000);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania świadectwa
    $('#certificatesTable').delegate('button.edit', 'click', function() {
        var id = $(this).data('certificate_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/certificate/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) {
                $.when( $('tr[data-certificate_id="'+id+'"]').hide(500) ).then(  function() {
                    var lp = $('[data-certificate_id="'+id+'"]').children(':first').html();
                    $('tr[data-certificate_id="'+id+'"]').replaceWith(result);
                    $('tr[data-certificate_id="'+id+'"]').hide().show(500);
                    updateClick(id, lp);
                });
            },
            error: function() {
                var error = '<tr><td class="error" colspan="6">Błąd tworzenia wiersza z formularzem dodawania deklaracji.</td></tr>';
                $('tr[data-certificate_id="'+id+'"]').before(error);
                $('td.error').hide().show(500);
            },
        });
    });
}

function updateClick(id, lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania świadectwa
    $('.cancelUpdate').click(function() {
        $.when( $('tr[data-certificate_id='+id+']').hide(500) ).then(  function() {
            refreshRow( id, lp );
        });
    });

    $('.update').click(function(){
        $.when( $('tr[data-certificate_id='+id+']').hide(500) ).then(  function() {
            update(id, lp);
        });
    });
}

function update(id, lp) {   // zapisanie zmienionego świadectwa w bazie danych
    var student_id      = $('.editRow[data-certificate_id='+id+'] input[name="student_id"]').val();
    var type            = $('.editRow[data-certificate_id='+id+'] select[name="type"]').val();
    var templates_id    = $('.editRow[data-certificate_id='+id+'] select[name="templates_id"]').val();
    var council_date    = $('.editRow[data-certificate_id='+id+'] input[name="council_date"]').val();
    var date_of_issue   = $('.editRow[data-certificate_id='+id+'] input[name="date_of_issue"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/certificate/"+id,
        data: { id: id, student_id: student_id, type: type, templates_id: templates_id, council_date: council_date, date_of_issue: date_of_issue },
        success: function() {   refreshRow( id, lp );   },
        error: function() {
            var error = '<tr><td class="error" colspan="6">Błąd! Nie można zapisać zmian.</td></tr>';
            $('[data-certificate_id="'+id+'"]').before(error);
            $('td.error').hide().show(1000);
        },
    });
}

function destroyClick() {  // usunięcie świadectwa (z bazy danych)
    $('#certificatesTable').delegate('.destroy', 'click', function() {
        var id = $(this).data('certificate_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/certificate/" + id,
            success: function() {
                $.when( $('tr[data-certificate_id="'+id+'"]').hide(750) ).then(function() {
                    $('tr[data-certificate_id="'+id+'"]').remove();
                });
            },
            error: function() {
                var error = '<tr><td class="error" colspan="6">Błąd! Nie można usunąć świadectwa.</td></tr>';
                $('tr[data-certificate_id="'+id+'"]').before(error);
                $('td.error').hide().show(1000);                
            }
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});