// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ------------------------ //
// --------------------- wydarzenia na stronie wyświetlania lat szkolnych ---------------------- //
var fadeOutTime = 575, fadeInTime = 1275;

function refreshRow(id, lp, add="true") {  // odświeżenie wiersza z informacjami o roku szkolnym o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rok_szkolny/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { refreshRowSuccess(id, result, add); },
        error: function() { refreshRowError(id, add); },
    });
}

function refreshRowError(id, add) {
    var error = '<tr data-school_year_id="'+id+'"><td class="error" colspan="8">Błąd odświeżania wiersza z informacjami o roku szkolnym.</td></tr>';
    if(add) $('tr.create').before(error);
    else $('tr[data-school_year_id="'+id+'"]').replaceWith(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function refreshRowSuccess(id, result, add) {
    if(add) { refreshRowSuccessForAdd(result); }
    else    { refreshRowSuccessForEdit(id, result); }
}

function refreshRowSuccessForAdd(result) {
    $('tr.create').before(result);
    $('tr[data-school_year_id="'+id+'"]').fadeIn(fadeInTime);
    $('#showCreateRow').show();
}

function refreshRowSuccessForEdit(id, result) {
    $.when( $('tr[data-school_year_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
        $('tr[data-school_year_id="'+id+'"]').replaceWith(result);
        $('tr[data-school_year_id="'+id+'"]').hide().fadeIn(fadeInTime);    
    });
}

// ------------------------------- zarządzanie latami szkolnymi -------------------------------- //
// ------------------------------ tworzenie formularza dodawania ------------------------------- //
function showCreateRowButtonClick() {
    $('#showCreateRow').click(function(){
        $('table#schoolYears').animate({width: '80%'}, 500);
        $.when( $(this).fadeOut(fadeOutTime) ).then(function() { showCreateRow(); });
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rok_szkolny/create",
        success: function(result) { showCreateRowSuccess(result); },
        error: function() { showCreateRowError(); },
    });
}

function showCreateRowError() {
    var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem dodawania roku szkolnego.</td></tr>';
    $('table#schoolYears tr.create').after(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function showCreateRowSuccess(result) {
    $('table#schoolYears tr.create').before(result);
    $('#createRow').hide().fadeIn(fadeInTime);
    $('input[name="dateStart"]').focus();
}

// --------------------------------- dodawanie roku szkolnego ---------------------------------- //
function createRowClickButtons() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania roku szkolnego
    $('table#schoolYears').delegate('#cancelAdd', 'click', function() { createRowClickCancel(); });
    $('table#schoolYears').delegate('#add', 'click', function() { createRowClickAdd(); });
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
        success: function(id) { addSuccess(id); },
        error: function() { addError(); },
    });
}

function addError() {
    var error = '<tr><td colspan="8" class="error">Błąd wczasie dodawania roku szkolnego.</td></tr>';
    $('table#schoolYears tr.create').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function addSuccess(id) {
    var lp = parseInt( $('#countSchoolYears').val() ) + 1;
    refreshRow(id, lp);
    $('#countSchoolYears').html(lp);
}

// ------------------------------- modyfikowanie roku szkolnego -------------------------------- //
function editButtonClick() {     // kliknięcie przycisku modyfikowania roku szkolnego
    $('#schoolYears').delegate('button.edit', 'click', function() {
        var id = $(this).data('school_year_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/"+id+"/edit",
            data: { id: id },
            success: function(result) { editButtonClickSuccess(id, result); },
            error: function() { editButtonClickError(id); },
        });
    });
}

function editButtonClickError(id) {
    var error = '<tr><td colspan="8" class="error">Nie mogę utworzyć formularza do zmiany danych.</td></tr>';
    $('tr[data-school_year_id="'+id+'"]').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function editButtonClickSuccess(id, result) {
    $.when( $('tr[data-school_year_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() {
        var lp = $('tr[data-school_year_id="'+id+'"]').children(":first").html();
        $('tr[data-school_year_id="'+id+'"]').replaceWith(result);
        $('tr.editRow[data-school_year_id="'+id+'"]').hide().fadeIn(fadeInTime);
        $('input[name="dateStart"]').focus();
        editRowClickButtons(lp);
    });

}

function editRowClickButtons(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania roku szkolnego
    $('.cancelUpdate').click(function() { editRowClickCancel( $(this).data('school_year_id'), lp ); });
    $('.update').click(function()       { editRowClickUpdate( $(this).data('school_year_id'), lp ); });
}

function editRowClickCancel(id, lp) {
    $.when( $('.editRow[data-school_year_id="'+id+'"]').fadeOut(fadeOutTime) ).then(function() { refreshRow(id, lp, false); });
}

function editRowClickUpdate(id, lp) {
    $.when( $('.editRow[data-school_year_id='+id+']').fadeOut(fadeOutTime) ).then( function() { update(id, lp); });
}

function update(id, lp) {   // zapisanie zmian w bazie danych
    var dateStart                                   = $('tr[data-school_year_id='+id+'] input[name="dateStart"]').val();
    var dateEnd                                     = $('tr[data-school_year_id='+id+'] input[name="dateEnd"]').val();
    var date_of_classification_of_the_last_grade    = $('tr[data-school_year_id='+id+'] input[name="date_of_classification_of_the_last_grade"]').val();
    var date_of_graduation_of_the_last_grade        = $('tr[data-school_year_id='+id+'] input[name="date_of_graduation_of_the_last_grade"]').val();
    var date_of_classification                      = $('tr[data-school_year_id='+id+'] input[name="date_of_classification"]').val();
    var date_of_graduation                          = $('tr[data-school_year_id='+id+'] input[name="date_of_graduation"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rok_szkolny/"+id,
        data: { id: id, date_start: dateStart, date_end: dateEnd, date_of_classification_of_the_last_grade: date_of_classification_of_the_last_grade,
            date_of_graduation_of_the_last_grade: date_of_graduation_of_the_last_grade, date_of_classification: date_of_classification, date_of_graduation: date_of_graduation },
        success: function() { refreshRow(id, lp, false); },
        error:   function() { updateError(id, lp); },
    });
}

function updateError(id, lp) {
    $.when( refreshRow(id, lp, false) ).then(function() {
        var error = '<tr><td colspan="8" class="error">Błąd modyfikowania roku_szkolnego.</td></tr>';
        $('tr[data-school_year_id='+id+']').before(error);
        $('td.error').hide().fadeIn(fadeInTime);
    });
}

// --------------------------------- usuwanie roku szkolnego ----------------------------------- //
function destroyButtonClick() {  // usunięcie roku szkolnego (z bazy danych)
    $('table#schoolYears').delegate('button.destroy', 'click', function() {
        var id = $(this).data('school_year_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/" + id,
            success: function() { destroySuccess(id); },
            error:   function() { destroyError(id); }
        });
        return false;
    });
}

function destroyError(id) {
    var error = '<tr data-school_year_id="'+id+'"><td colspan="8" class="error">Nie mogę usunąć roku szkolnego. Prawdopodobnie istnieją powiązane rekordy.</td></tr>';
    $('tr[data-school_year_id='+id+']').before(error);
    $('td.error').hide().fadeIn(fadeInTime);
}

function destroySuccess(id) {
    $.when( $('tr[data-school_year_id='+id+']').fadeOut(fadeOutTime) ).then( function() {
        $('tr[data-school_year_id='+id+']').remove();
     });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    showCreateRowButtonClick();
    createRowClickButtons();
    editButtonClick();
    destroyButtonClick();
});