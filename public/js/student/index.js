// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 22.02.2023 ------------------------ //
// ---------------------------- wydarzenia dla widoku uczen/index  ----------------------------- //
const FADE_OUT=575, SLIDE_UP=1250, SLIDE_DOWN=1250;
const NUMBER_OF_FIELDS=11, TABLE_NAME="#students", DATA_NAME="student_id", INPUT_NAME="first_name", ROUTE_NAME="uczen";

import '../patternForIndex.js';
import { CreateRowService, RefreshRowService, EditRowService } from '../patternForIndex.js';


// ------------------------ wydarzenia na stronie wyświetlania uczniów ------------------------- //
function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

function gradeChanged() {  // wybór klasy w polu select
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/klasa/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

// ------------------- odświeżanie wiersza tabeli z informacjami o rekordzie ------------------- //
function refreshRow(id, lp, operation="add", success="true") {   // odświeżenie wiersza z informajami o rekordzie o podanym identyfikatorze
    var RefreshRow = new RefreshRowService(NUMBER_OF_FIELDS, TABLE_NAME, DATA_NAME);
    if(!success)
        switch(operation) {
            case "update": RefreshRow.updateError(id, "Błąd w trakcie modyfikowania ucznia."); break;
            case "add": RefreshRow.addError("Błąd w trakcie dodawania ucznia."); return;
            case "destroy": RefreshRow.destroyError(id, "Nie można usunąć ucznia. Prawdopodobnie istnieją powiązane rekordy."); return;
        }
    if(operation=="destroy")        { RefreshRow.destroySuccess(id); return; }

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { RefreshRow.success(id, result, operation); },
        error: function() { RefreshRow.error(id, operation, "Błąd odświeżania wiersza z ucznia."); },
    });
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// --------- formularz dodawania: tworzenie formularza, anulowanie i dodawanie rekordu --------- //
function clickCreateRowButtons() {   // kliknięcie przycisku dodawania
    $('#showCreateRow').click(function(){       // tworzenie formularza dodawania
        $('tr#createRow').remove();
        var communique = "Błąd tworzenia wiersza z formularzem dodawania ucznia.";
        var CreateRow = new CreateRowService(NUMBER_OF_FIELDS, TABLE_NAME, communique, INPUT_NAME);
        $(TABLE_NAME).animate({width: '95%'}, 500);
        $.when( $(this).slideUp(SLIDE_UP) ).then(function() { CreateRow.show(ROUTE_NAME); });
    });

    $(TABLE_NAME).delegate('button#cancelAdd', 'click', function() {    // kliknięcie przycisku "anuluj"
        $.when( $('#createRow').fadeOut(FADE_OUT) ).then(function() {
            $('#createRow').remove();
            $('#showCreateRow').slideDown(SLIDE_DOWN);
        });
    });

    $(TABLE_NAME).delegate('button#add', 'click', function() {          // kliknięcie przycisku "dodaj"
        $.when( $('#createRow').fadeOut(FADE_OUT) ).then(function() {
            $('#showCreateRow').slideDown(SLIDE_DOWN);
            add();
        });
    });
}

// ----------------------------- wstawienie rekordu do bazy danych ----------------------------- //
function add() {
    var first_name      = $('#createRow input[name="first_name"]').val();
    var second_name     = $('#createRow input[name="second_name"]').val();
    var last_name       = $('#createRow input[name="last_name"]').val();
    var family_name     = $('#createRow input[name="family_name"]').val();
    var sex             = $('#createRow select[name="sex"]').val();
    var PESEL           = $('#createRow input[name="PESEL"]').val();
    var place_of_birth  = $('#createRow input[name="place_of_birth"]').val();
    var lp = parseInt( $('#countStudents').val() ) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME,
        data: { first_name: first_name, second_name: second_name, last_name: last_name, family_name: family_name, sex: sex, PESEL: PESEL, place_of_birth: place_of_birth },
        success: function(id) {
            lp += " ("+id+")";
            refreshRow(id, lp, "add", true);
            getStudentGradeCreateForm(id);
        },
        error: function() { refreshRow(0, lp, "add", false); },
    });
}

function getStudentGradeCreateForm(student_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/klasy_ucznia/create",
        data: { student_id: student_id, version: "forStudent" },
        success: function(result) {
            $('tr[data-' +DATA_NAME+ '="'+student_id+'"]').after(result);
            proposedDateClick();
            addStudentGradeClick();
            var tr = '<tr class="createFormHeader"><td class="c bg-danger lead text-danger" colspan="11">Dodaj klasę dla ucznia</td></tr>';
            $('tr[data-' +DATA_NAME+ '="'+student_id+'"]').after(tr);
        },
        error: function() {
            alert('error');
            var error = '<tr><td colspan="6" class="error">Błąd tworzenia wiersza zformularzem dla dodawania klasy ucznia.</td></tr>';
            $('#studentGrades tr.create').before(error);
        },
    });
}

function addStudentGradeClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania klasy ucznia
    $('#students button.cancelAdd').click(function(){
        $.when( $('.createFormHeader').hide(SLIDE_DOWN) ).then(function() { $('.createFormHeader').remove(); });
        $.when( $('#studentGradeCreateRow').hide(SLIDE_DOWN) ).then(function() { $('#studentGradeCreateRow').remove(); });
        $.when( $('#studentGradeProposedDates').hide(SLIDE_DOWN) ).then(function() { $('#studentGradeProposedDates').remove(); });
    });

    $('#students button.add').click(function(){
        addStudentGrade();
        $.when( $('.createFormHeader').hide(SLIDE_DOWN) ).then(function() { $('.createFormHeader').remove(); });
        $.when( $('#studentGradeCreateRow').hide(SLIDE_DOWN) ).then(function() { $('#studentGradeCreateRow').remove(); });
        $.when( $('#studentGradeProposedDates').hide(SLIDE_DOWN) ).then(function() { $('#studentGradeProposedDates').remove(); });
    });
}

function proposedDateClick() {
    $('.studentGradeStart').bind('click', function(){  $('#start').val($(this).html());  });
    $('.studentGradeEnd').bind(  'click', function(){  $('#end').val($(this).html());  });
}

function addStudentGrade() {   // zapisanie klasy ucznia w bazie danych
    var student_id  = $('#studentGradeCreateRow input[name="student_id"]').val();
    var grade_id    = $('#studentGradeCreateRow select[name="grade_id"]').val();
    var start  = $('#studentGradeCreateRow input[name="start"]').val();
    var end    = $('#studentGradeCreateRow input[name="end"]').val();
    var confirmation_start = $('#studentGradeCreateRow input[name="confirmationStart"]').is(':checked');
    var confirmation_end   = $('#studentGradeCreateRow input[name="confirmationEnd"]').is(':checked');
    if(confirmation_start)  confirmation_start=1;
    if(confirmation_end)  confirmation_end=1;
    var lp = parseInt($('#lp').val())+1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/klasy_ucznia",
        data: { student_id: student_id, grade_id: grade_id, start: start, end: end, confirmation_start: confirmation_start, confirmation_end: confirmation_end },
        success: function() {
            var tr = '<tr><td></td><td class="error" colspan="9">Dodano klasę dla ucznia</td><td></td></tr>';
            $('tr[data-' +DATA_NAME+ '="'+student_id+'"]').after(tr);
        },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Błąd dodawania klasy dla ucznia.</td></tr>';
            $('tr[data-' +DATA_NAME+ '="'+student_id+'"]').before(error);
        },
    });
}

// -------- formularz modyfikowania: tworzenie formularza, anulowanie i zmiana rekordu --------- //
function clickEditRowButtons() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany danych ucznia.";
        var EditRow = new EditRowService(NUMBER_OF_FIELDS, DATA_NAME, communique, INPUT_NAME);
        var id = $(this).data(DATA_NAME);
        var lp = $('tr[data-' +DATA_NAME+ '="'+id+'"]').children(":first").html();
        EditRow.show(ROUTE_NAME, id, lp);
    });

    $(TABLE_NAME).delegate('button.cancelUpdate', 'click', function() { // kliknięcie przycisku "anuluj"
        var id = $(this).data(DATA_NAME);
        var lp = $('var.lp').html();
        $.when( $('.editRow[data-' +DATA_NAME+ '=' +id+ ']').fadeOut(FADE_OUT) ).then( function() { refreshRow(id, lp, "cancelUpdate", true); });
    });

    $(TABLE_NAME).delegate('button.update', 'click', function() {       // kliknięcie przycisku "zapisz"
        var id = $(this).data(DATA_NAME);
        var lp = $('var.lp').html();
        $.when( $('.editRow[data-' +DATA_NAME+ '=' +id+ ']').fadeOut(FADE_OUT) ).then( function() { update(id, lp); });
    });
}

// --------------------------------- zapisywanie zmian rekordu --------------------------------- //
function update(id, lp) {
    var first_name      = $('tr[data-student_id="'+id+'"] input[name="first_name"]').val();
    var second_name     = $('tr[data-student_id="'+id+'"] input[name="second_name"]').val();
    var last_name       = $('tr[data-student_id="'+id+'"] input[name="last_name"]').val();
    var family_name     = $('tr[data-student_id="'+id+'"] input[name="family_name"]').val();
    var sex             = $('tr[data-student_id="'+id+'"] select[name="sex"]').val();
    var PESEL           = $('tr[data-student_id="'+id+'"] input[name="PESEL"]').val();
    var place_of_birth  = $('tr[data-student_id="'+id+'"] input[name="place_of_birth"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
        data: { id: id, first_name: first_name, second_name: second_name, last_name: last_name, family_name: family_name, sex: sex, PESEL: PESEL, place_of_birth: place_of_birth },
        success: function() { refreshRow(id, lp, "update", true); },
        error:   function() { refreshRow(id, lp, "update", false); },
    });
}

// ------------------------------------- usuwanie rekordu -------------------------------------- //
function clickDestroyButton() {
    $(TABLE_NAME).delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/" + id,
            success: function() { refreshRow(id, 0, "destroy", true); },
            error:   function() { refreshRow(id, 0, "destroy", false); }
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChanged();
    schoolYearChanged();
    clickCreateRowButtons();
    clickEditRowButtons();
    clickDestroyButton();
});