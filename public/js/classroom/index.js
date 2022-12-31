// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ------------------------ //
// --------------------- wydarzenia na stronie wyświetlania sal lekcyjnych --------------------- //
var fadeOutTime = 575, fadeInTime = 1275;

function refreshRow(id, lp, add="true") {  // odświeżenie wiersza z informajami o sali lekcyjnej o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sala/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { refreshRowSuccess(id, result, add); },
        error: function() { refreshRowError(id, add); },
    });
}

function refreshRowError(id, add) {
    var error = '<tr data-classroom_id="'+id+'"><td class="error" colspan="7">Błąd odświeżania wiersza z informacjami o sali lekcyjnej.</td></tr>';
    if(add) $('tr.create').before(error);
    else $('tr[data-classroom_id="'+id+'"]').replaceWith(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function refreshRowSuccess(id, result, add) {
    if(add) { refreshRowSuccessForAdd(result); }
    else    { refreshRowSuccessForEdit(id, result); }
}

function refreshRowSuccessForAdd(result) {
    $('tr.create').before(result);
    $('tr[data-classroom_id="'+id+'"]').fadeIn(fadeInTime);
    $('#showCreateRow').show();
}

function refreshRowSuccessForEdit(id, result) {
    $.when( $('tr[data-classroom_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
        $('tr[data-classroom_id="'+id+'"]').replaceWith(result);
        $('tr[data-classroom_id="'+id+'"]').hide().fadeIn(fadeInTime);    
    });
}

// ----------------------------------- zarządzanie szkołami ------------------------------------ //
// ------------------------------ tworzenie formularza dodawania ------------------------------- //
function showCreateRowButtonClick() {
    $('#showCreateRow').click(function(){
        $('#classrooms').animate({width: '60%'}, 500);
        $.when( $(this).fadeOut(fadeOutTime) ).then(function() { showCreateRow(); });
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sala/create",
        success: function(result) { showCreateRowSuccess(result); },
        error: function() { showCreateRowError(); },
    });
}

function showCreateRowError() {
    var error = '<tr><td colspan="7" class="error">Błąd tworzenia wiersza z formularzem dodawania sali lekcyjnej.</td></tr>';
    $('#classrooms tr.create').after(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function showCreateRowSuccess(result) {
    $('#classrooms tr.create').before(result);
    $('#createRow').hide().fadeIn(fadeInTime);
    $('input[name="name"]').focus();
}

// --------------------------------- dodawanie sali lekcyjnej ---------------------------------- //
function createRowClickButtons() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania sali lekcyjnej
    $('#classrooms').delegate('#cancelAdd', 'click', function() { createRowClickCancel(); });
    $('#classrooms').delegate('#add', 'click', function() { createRowClickAdd(); });
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

function add() {   // zapisanie sali lekcyjnej w bazie danych
    var name    = $('#createRow input[name="name"]').val();
    var capacity  = $('#createRow input[name="capacity"]').val();
    var floor  = $('#createRow input[name="floor"]').val();
    var line  = $('#createRow input[name="line"]').val();
    var column  = $('#createRow input[name="column"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sala",
        data: { name: name, capacity: capacity, floor: floor, line: line, column: column },
        success: function(id) { addSuccess(id); },
        error: function() { addError(); },
    });
}

function addError() {
    var error = '<tr><td colspan="7" class="error">Błąd dodawania sali lekcyjnej.</td></tr>';
    $('#classrooms tr.create').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function addSuccess(id) {
    var lp = parseInt( $('#countClassrooms').val() ) + 1;
    refreshRow(id, lp);
    $('#countClassrooms').html(lp);
}

// ------------------------------- modyfikowanie sali lekcyjnej -------------------------------- //
function editButtonClick() {     // kliknięcie przycisku modyfikowania sali lekcyjnej
    $('#classrooms').delegate('button.edit', 'click', function() {
        var id = $(this).data('classroom_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sala/"+id+"/edit",
            data: { id: id },
            success: function(result) { editButtonClickSuccess(id, result); },
            error: function() { editButtonClickError(id); },
        });
    });
}

function editButtonClickError(id) {
    var error = '<tr><td colspan="7" class="error">Nie mogę utworzyć formularza do zmiany danych.</td></tr>';
    $('tr[data-classroom_id="'+id+'"]').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function editButtonClickSuccess(id, result) {
    $.when( $('tr[data-classroom_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
        var lp = $('tr[data-classroom_id="'+id+'"]').children(":first").html();
        $('tr[data-classroom_id="'+id+'"]').replaceWith(result);
        $('tr.editRow[data-classroom_id="'+id+'"]').hide().fadeIn(fadeInTime);
        $('input[name="name"]').focus();
        editRowClickButtons(lp);
    });
}

function editRowClickButtons(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania sali lekcyjnej
    $('.cancelUpdate').click(function() { editRowClickCancel( $(this).data('classroom_id'), lp ); });
    $('.update').click(function()       { editRowClickUpdate( $(this).data('classroom_id'), lp ); });
}

function editRowClickCancel(id, lp) {
    $.when( $('.editRow[data-classroom_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() { refreshRow(id, lp, false); });
}

function editRowClickUpdate(id, lp) {
    $.when( $('.editRow[data-classroom_id='+id+']').fadeOut(fadeOutTime) ).then( function() { update(id, lp); });
}

function update(id, lp) {   // zapisanie zmian w bazie danych
    var name    = $('tr[data-classroom_id="'+id+'"] input[name="name"]').val();
    var capacity= $('tr[data-classroom_id="'+id+'"] input[name="capacity"]').val();
    var floor   = $('tr[data-classroom_id="'+id+'"] input[name="floor"]').val();
    var line    = $('tr[data-classroom_id="'+id+'"] input[name="line"]').val();
    var column  = $('tr[data-classroom_id="'+id+'"] input[name="column"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/sala/"+id,
        data: { id: id, name: name, capacity: capacity, floor: floor, line: line, column: column },
        success: function() { refreshRow(id, lp, false); },
        error:   function() { updateError(id, lp); },
    });
}

function updateError(id, lp) {
    $.when( refreshRow(id, lp, false) ).then(function() {
        var error = '<tr><td colspan="7" class="error">Błąd w czasie zapisywania informacji.</td></tr>';
        $('tr[data-classroom_id='+id+']').before(error);
        $('td.error').hide().fadeIn(fadeInTime);
    });
}

// --------------------------------- usuwanie sali lekcyjnej ----------------------------------- //
function destroyButtonClick() {  // usunięcie sali lekcyjnej (z bazy danych)
    $('#classrooms').delegate('button.destroy', 'click', function() {
        var id = $(this).data('classroom_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sala/" + id,
            success: function() { destroySuccess(id); },
            error:   function() { destroyError(id); }
        });
        return false;
    });
}

function destroyError(id) {
    var error = '<tr data-classroom_id="'+id+'"><td colspan="7" class="error">Nie mogę usunąć sali lekcyjnej. Prawdopodobnie istnieją powiązane rekordy.</td></tr>';
    $('tr[data-classroom_id='+id+']').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function destroySuccess(id) {
    $.when( $('tr[data-classroom_id='+id+']').fadeOut(fadeOutTime) ).then( function() {
        $('tr[data-classroom_id='+id+']').remove();
     });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowButtonClick();
    createRowClickButtons();
    editButtonClick();
    destroyButtonClick();
});