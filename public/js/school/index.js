// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 30.12.2022 ------------------------ //
// ------------------------- wydarzenia na stronie wyświetlania szkół -------------------------- //
var fadeOutTime = 575, fadeInTime = 1275;

function refreshRow(id, lp, add="true") {  // odświeżenie wiersza z informajami o szkole o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/szkola/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { refreshRowSuccess(id, result, add); },
        error: function() { refreshRowError(id, add); },
    });
}

function refreshRowError(id, add) {
    var error = '<tr data-school_id="'+id+'"><td class="error" colspan="4">Błąd odświeżania wiersza z informacjami o szkole.</td></tr>';
    if(add) $('tr.create').before(error);
    else $('tr[data-school_id="'+id+'"]').replaceWith(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function refreshRowSuccess(id, result, add) {
    if(add) { refreshRowSuccessForAdd(result); }
    else    { refreshRowSuccessForEdit(id, result); }
}

function refreshRowSuccessForAdd(result) {
    alert('refreshSuccess Add');
    $('tr.create').before(result);
    $('tr[data-school_id="'+id+'"]').fadeIn(fadeInTime);
    $('#showCreateRow').show();
}

function refreshRowSuccessForEdit(id, result) {
    $.when( $('tr[data-school_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
        $('tr[data-school_id="'+id+'"]').replaceWith(result);
        $('tr[data-school_id="'+id+'"]').hide().fadeIn(fadeInTime);    
    });
}

// ----------------------------------- zarządzanie szkołami ------------------------------------ //
function showCreateRowButtonClick() {
    $('#showCreateRow').click(function(){
        $('table#schools').animate({width: '80%'}, 500);
        $.when( $(this).fadeOut(fadeOutTime) ).then(function() { showCreateRow(); });
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/szkola/create",
        success: function(result) { showCreateRowSuccess(result); },
        error: function() { showCreateRowError(); },
    });
}

function showCreateRowError() {
    var error = '<tr><td colspan="4" class="error">Błąd tworzenia wiersza z formularzem dodawania szkoły.</td></tr>';
    $('table#schools tr.create').after(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function showCreateRowSuccess(result) {
    $('table#schools tr.create').before(result);
    $('#createRow').hide().fadeIn(fadeInTime);
    $('input[name="name"]').focus();
}

function createRowClickButtons() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania szkoły
    $('table#schools').delegate('#cancelAdd', 'click', function() { createRowClickCancel(); });
    $('table#schools').delegate('#add', 'click', function() { createRowClickAdd(); });
}

function createRowClickCancel() {
    $.when( $('#createRow').fadeOut(fadeOutTime) ).then(function() {
        $('#createRow').remove();
        $('#showCreateRow').fadeIn(fadeInTime);
    });
}

function createRowClickAdd() {
    $.when( $('#createRow').fadeOut(fadeOutTime) ).then(function() {
        $('#showCreateRow').fadeIn(fadeInTime);
        add();
    });
}

function add() {   // zapisanie nauczyciela w bazie danych
    var name    = $('#createRow input[name="name"]').val();
    var id_OKE  = $('#createRow input[name="id_OKE"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/szkola",
        data: { name: name, id_OKE: id_OKE },
        success: function(id) { addSuccess(id); },
        error: function() { addError(); },
    });
}

function addError() {
    var error = '<tr><td colspan="5" class="error">Błąd dodawania szkoły.</td></tr>';
    $('table#schools tr.create').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function addSuccess() {
    var lp = parseInt( $('#countSchools').val() ) + 1;
    refreshRow(id, lp);
    $('#countTeachers').html(lp);
}

function editButtonClick() {     // kliknięcie przycisku modyfikowania szkoły
    $('#schools').delegate('button.edit', 'click', function() {
        var id = $(this).data('school_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/szkola/"+id+"/edit",
            data: { id: id },
            success: function(result) { editButtonClickSuccess(id, result); },
            error: function() { editButtonClickError(id); },
        });
    });
}

function editButtonClickError(id) {
    var error = '<tr><td colspan="4" class="error">Nie mogę utworzyć formularza do zmiany danych.</td></tr>';
    $('tr[data-school_id="'+id+'"]').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function editButtonClickSuccess(id, result) {
    $.when( $('tr[data-school_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
        var lp = $('tr[data-school_id="'+id+'"]').children(":first").html();
        $('tr[data-school_id="'+id+'"]').replaceWith(result);
        $('tr.editRow[data-school_id="'+id+'"]').hide().fadeIn(fadeInTime);
        $('input[name="name"]').focus();
        editRowClickButtons(lp);
    });

}

function editRowClickButtons(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania szkoły
    $('.cancelUpdate').click(function() { editRowClickCancel( $(this).data('school_id'), lp ); });
    $('.update').click(function()       { editRowClickUpdate( $(this).data('school_id'), lp ); });
}

function editRowClickCancel(id, lp) {
    $.when( $('.editRow[data-school_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() { refreshRow(id, lp, false); });
}

function editRowClickUpdate(id, lp) {
    $.when( $('.editRow[data-school_id='+id+']').fadeOut(fadeOutTime) ).then( function() { update(id, lp); });
}

function update(id, lp) {   // zapisanie zmian w bazie danych
    var name    = $('tr[data-school_id="'+id+'"] input[name="name"]').val();
    var id_OKE  = $('tr[data-school_id="'+id+'"] input[name="id_OKE"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/szkola/"+id,
        data: { id: id, name: name, id_OKE: id_OKE },
        success: function() { refreshRow(id, lp, false); },
        error:   function() { updateError(id, lp); },
    });
}

function updateError(id, lp) {
    $.when( refreshRow(id, lp, false) ).then(function() {
        var error = '<tr><td colspan="4" class="error">Błąd w czasie zapisywania informacji.</td></tr>';
        $('tr[data-school_id='+id+']').before(error);
        $('td.error').hide().fadeIn(fadeInTime);
    });
}

function destroyButtonClick() {  // usunięcie szkoły (z bazy danych)
    $('table#schools').delegate('button.destroy', 'click', function() {
        var id = $(this).data('school_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/szkola/" + id,
            success: function() { destroySuccess(id); },
            error:   function() { destroyError(id); }
        });
        return false;
    });
}

function destroyError(id) {
    var error = '<tr data-school_id="'+id+'"><td colspan="4" class="error">Nie mogę usunąć szkoły. Prawdopodobnie istnieją powiązane rekordy.</td></tr>';
    $('tr[data-school_id='+id+']').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function destroySuccess(id) {
    $.when( $('tr[data-school_id='+id+']').fadeOut(fadeOutTime) ).then( function() {
        $('tr[data-school_id='+id+']').remove();
     });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowButtonClick();
    createRowClickButtons();
    editButtonClick();
    destroyButtonClick();
});