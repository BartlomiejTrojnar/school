// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 05.01.2022 ------------------------ //
// --------------------- obsługa wydarzeń strony z numerami księgi uczniów --------------------- //

function schoolChanged() {  // wybór szkoły w polu select
    $('select[name="school_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/szkola/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function(res) {
                var error = '<p class="error">Błąd odświeżania strony przy zmianie szkoły.<br />'+res+'</p>';
                $('section#main-content').prepend(error);
            },
        });
        return false;
    });
}

function refreshRow(id, type="add", lp=99) {  // odświeżenie wiersza tabeli z numerem księgi dla ucznia
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/book_of_student/refreshRow",
        data: { id: id, version: "forIndex", lp: lp },
        success: function(result) {
            if(type=="add") {
                $("tr[data-book_of_student_id='"+id+"']").replaceWith(result);
                $("tr[data-book_of_student_id='"+id+"']").show(750);
                $('#showCreateRow').show();
            }
            else {
                $("tr.editRow[data-book_of_student_id='"+id+"']").remove();
                $("tr[data-book_of_student_id='"+id+"']").replaceWith(result);
                $("tr[data-book_of_student_id='"+id+"']").show(750);
    
            }
        },
        error: function() {
            var error = '<td colspan="7" class="error">Błąd odświeżania wiersza z numerem księgi ucznia!</td>';
            $("tr[data-book_of_student_id='"+id+"']").html(error);
        },
    });
}

// ------------------ dodawanie, zmiana i usuwanie numerów w księdze uczniów ------------------- //
function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $('tr.create').before('<tr id="createRow"><td colspan="7">Ładowanie formularza dla dodawania numeru księgi ucznia.</td></tr>');
        $.ajax({
            method: "GET",
            url: "http://localhost/school/ksiega_uczniow/create",
            data: { version: "forIndex" },
            success: function(result) {
                $.when( $('#showCreateRow').hide(500) ).then(function () {
                    $("#createRow").replaceWith(result);
                    $("#createRow").show(750);
                });
            },
            error: function() {
                var error = '<td colspan="7" class="error">Błąd tworzenia wiersza z formularzem dodawania numeru księgi ucznia.</td>';
                $("#createRow").html(error);
            },
        });
        return false;
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania numeru księgi uczniów
    $('#bookOfStudents').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').slideDown(750);
    });

    $('#bookOfStudents').delegate('#add', 'click', function() {
        $.when( add() ).then(function() {
            $('#createRow').remove();
            $('#showCreateRow').slideDown(750);    
        })
    });
}

function add() {   // zapisanie numeru księgi ucznia do bazy danych
    var student_id  = $('#createRow select[name="student_id"]').val();
    var school_id   = $('#createRow select[name="school_id"]').val();
    var number      = $('#createRow input[name="number"]').val();
    var lp = $('#lastLP').val()-1+2;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow",
        data: { student_id: student_id, school_id: school_id, number: number },
        success: function(result) {
            $('#bookOfStudents tr.create').before('<tr data-book_of_student_id="'+result+'"><td colspan="7">'+result+'</td></tr>');
            $("#lastLP").val( lp );
            refreshRow(result, "add", lp);
        },
        error: function() {
            var error = '<td colspan="7" class="error">Błąd dodawania numeru księgi ucznia!</td>';
            $('tr.create').html(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania numeru księgi ucznia
    $('#bookOfStudents').delegate('button.edit', 'click', function() {
        var id = $(this).data('book_of_student_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ksiega_uczniow/"+id+"/edit",
            data: { id: id, version: "forIndex" },
            success: function(result) {
                $("tr[data-book_of_student_id='"+id+"']").before(result).hide(750);
                updateClick();
            },
            error: function() {
                var error = '<td colspan="7" class="error">Nie można utworzyć formularza dla modyfikowania numeru księgi ucznia!</td>';
                $("tr[data-book_of_student_id='"+id+"']").html(error);
            }
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania numeru księgi ucznia
    $('button.cancelUpdate').click(function(){
        var id = $(this).data('book_of_student_id');
        $.when( $('tr.editRow[data-book_of_student_id='+id+']').hide(750) ).then(function () {
            $('tr.editRow[data-book_of_student_id='+id+']').remove();
            $('tr[data-book_of_student_id='+id+']').show(750);
        });
    });

    $('button.update').click(function(){
        var id = $(this).data('book_of_student_id');
        $.when( $('tr.editRow[data-book_of_student_id='+id+']').hide(750) ).then(function () {
            $('tr.editRow[data-book_of_student_id='+id+']').remove();
            $('tr[data-book_of_student_id='+id+']').show(750);
        });
        update(id);
    });
}

function update(id) {   // zapisanie numeru księgi ucznia w bazie danych
    var student_id  = $('tr[data-book_of_student_id="'+id+'"] select[name="student_id"]').val();
    var school_id   = $('tr[data-book_of_student_id="'+id+'"] select[name="school_id"]').val();
    var number      = $('tr[data-book_of_student_id="'+id+'"] input[name="number"]').val();
    var lp          = $('tr[data-book_of_student_id='+id+'] td.lp').html();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow/"+id,
        data: { student_id: student_id, school_id: school_id, number: number },
        success: function() { refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<td colspan="7" class="error">Nie mogę zmienić numeru księgi ucznia!</td>';
            $("tr[data-book_of_student_id='"+id+"']").html(error);
        },
    });
}

function destroyClick() {  // usunięcie numeru księgi ucznia (z bazy danych)
    $('#bookOfStudents').delegate('button.destroy', 'click', function() {
        var id = $(this).attr('data-book_of_student_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ksiega_uczniow/" + id,
            success: function() {
                $.when( $('tr[data-book_of_student_id='+id+']').hide(750) ).then(function() {
                    $('tr[data-book_of_student_id='+id+']').remove();
                    $("#lastLP").val( $('#lastLP').val()-1 );
                });
            },
            error: function() {
                var error = '<td colspan="7" class="error">Nie można usunąć numeru księgi ucznia. Spróbuj później.</td>';
                $("tr[data-book_of_student_id='"+id+"']").html(error);
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