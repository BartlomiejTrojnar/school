// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 05.07.2022 ------------------------ //
// ------------- wydarzenia na stronie wyświetlania wyboru podręczników ------------------------ //

function subjectChanged() {  // wybór przedmiotu w polu select
    $('select[name="subject_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

// --------------------------------- zarządzanie podręcznikami --------------------------------- //
function refreshRow(id, version, lp=0) {  // odświeżenie wskazanego wiersza z iformacjami o podręczniku
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/textbook/refreshRow",
        data: { id: id, version: "forIndex", lp: lp },
        success: function(result) {
            if(version=="add"){
                $('tr.create').before(result);
                $('#showCreateRow').show();
                $('#countTextbooks').html(lp);
            }
            else {
                $('tr.editRow[data-textbook_id='+id+']').remove();
                $('tr[data-textbook_id='+id+']').replaceWith(result);
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="10">Błąd odświeżania wiersza podręcznika!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $(this).hide();
        $('#textbooks').animate({width: '1500px'}, 500);
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/podrecznik/create",
        data: { version: "forIndex" },
        success: function(result) {  $('#textbooks').append(result);  },
        error: function() {
            var error = '<tr><td colspan="10" class="error">Błąd tworzenia wiersza z formularzem dodawania podręcznika.</td></tr>';
            $('#textbooks tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania podręcznika
    $('#textbooks').delegate('#cancelAdd', 'click', function() {
        $('#createRow').remove();
        $('#showCreateRow').show();
    });

    $('#textbooks').delegate('#add', 'click', function() {
        add();
        $('#createRow').remove();
        $('#showCreateRow').show();
    });
}

function add() {   // zapisanie wyboru podręcznika w bazie danych
    var subject_id      = $('#createRow select[name="subject_id"]').val();
    var title           = $('#createRow textarea[name="title"]').val();
    var author          = $('#createRow textarea[name="author"]').val();
    var publishing_house= $('#createRow input[name="publishing_house"]').val();
    var admission       = $('#createRow input[name="admission"]').val();
    var comments        = $('#createRow input[name="comments"]').val();
    var lp = parseInt( $('#countTextbooks').html() )+1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/podrecznik",
        data: { subject_id: subject_id, title: title, author: author, publishing_house: publishing_house, admission: admission, comments: comments },
        success: function(id) {  refreshRow(id, "add", lp);  },
        error: function() {
            var error = '<tr><td colspan="10" class="error">Błąd dodawania podręcznika</td></tr>';
            $('#textbooks tr.create').before(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania podręcznika
    $('#textbooks').delegate('button.edit', 'click', function() {
        var id = $(this).data('textbook_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/podrecznik/"+id+"/edit",
            data: { id: id, version: "forIndex", lp: lp },
            success: function(result) {
                $('tr[data-textbook_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<tr><td colspan="10" class="error">Błąd tworzenia wiersza z formularzem modyfikowania podręcznika.</td></tr>';
                $('tr[data-textbook_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania podręcznika
    $('.cancelUpdate').click(function() {
        var id = $(this).data('textbook_id');
        $('.editRow[data-textbook_id='+id+']').remove();
        $('tr[data-textbook_id='+id+']').show();
    });

    $('.update').click(function(){
        update( $(this).data('textbook_id') );
    });
}

function update(id) {   // zapisanie wyboru podręcznika w bazie danych
    var subject_id      = $('tr[data-textbook_id='+id+'] select[name="subject_id"]').val();
    var title           = $('tr[data-textbook_id='+id+'] textarea[name="title"]').val();
    var author          = $('tr[data-textbook_id='+id+'] textarea[name="author"]').val();
    var publishing_house= $('tr[data-textbook_id='+id+'] input[name="publishing_house"]').val();
    var admission       = $('tr[data-textbook_id='+id+'] input[name="admission"]').val();
    var comments        = $('tr[data-textbook_id='+id+'] input[name="comments"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/podrecznik/"+id,
        data: { id: id, subject_id: subject_id, title: title, author: author, publishing_house: publishing_house, admission: admission, comments: comments },
        success: function() {
            var lp = $('tr[data-textbook_id='+id+'] input[name="lp"]').val();
            refreshRow(id, "edit", lp);
        },
        error: function() {
            var error = '<tr><td colspan="10" class="error">Błąd modyfikowania podręcznika.</td></tr>';
            $('tr[data-textbook_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie wyboru podręcznika (z bazy danych)
    $('#textbooks').delegate('.destroy', 'click', function() {
        var id = $(this).data('textbook_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/podrecznik/" + id,
            success: function() {  $('tr[data-textbook_id='+id+']').remove();  },
            error: function() {
                var error = '<tr><td colspan="10" class="error">Błąd usuwania podręcznika.</td></tr>';
                $('tr[data-textbook_id='+id+']').after(error).hide();
            }
        });
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    subjectChanged();

    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();
});