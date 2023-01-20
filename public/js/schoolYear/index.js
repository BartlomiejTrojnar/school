// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ------------------------ //
// ------------------------- wydarzenia dla widoku rok_szkolny/index  -------------------------- //
const FADE_OUT=575, SLIDE_UP=1250, SLIDE_DOWN=1250;
const NUMBER_OF_FIELDS=8, TABLE_NAME="#schoolYears", DATA_NAME="school_year_id", INPUT_NAME="dateStart", ROUTE_NAME="rok_szkolny";

import '../patternForIndex.js';
import { CreateRowService, RefreshRowService } from '../patternForIndex.js';
import { EditRowService } from '../patternForIndex.js';

// ------------------- odświeżanie wiersza tabeli z informacjami o rekordzie ------------------- //
function refreshRow(id, lp, operation="add", success="true") {   // odświeżenie wiersza z informajami o rekordzie o podanym identyfikatorze
    var RefreshRow = new RefreshRowService(NUMBER_OF_FIELDS, TABLE_NAME, DATA_NAME);
    if(!success)
        switch(operation) {
            case "update": RefreshRow.updateError(id, "Błąd w trakcie modyfikowania roku szkolnego."); break;
            case "add": RefreshRow.addError("Błąd w trakcie dodawania roku szkolnego."); return;
            case "destroy": RefreshRow.destroyError(id, "Nie można usunąć roku szkolnego. Prawdopodobnie istnieją powiązane rekordy."); return;
        }
    if(operation=="destroy")        { RefreshRow.destroySuccess(id); return; }

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { RefreshRow.success(id, result, operation); },
        error: function() { RefreshRow.error(id, operation, "Błąd odświeżania wiersza z informacjami o roku szkolnym."); },
    });
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// --------- formularz dodawania: tworzenie formularza, anulowanie i dodawanie rekordu --------- //
function clickCreateRowButtons() {   // kliknięcie przycisku dodawania
    $('#showCreateRow').click(function(){       // tworzenie formularza dodawania
        $('tr#createRow').remove();
        var communique = "Błąd tworzenia wiersza z formularzem dodawania roku szkolnego.";
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
    var dateStart = $('#createRow input[name="dateStart"]').val();
    var dateEnd = $('#createRow input[name="dateEnd"]').val();
    var date_of_classification_of_the_last_grade = $('#createRow input[name="date_of_classification_of_the_last_grade"]').val();
    var date_of_graduation_of_the_last_grade = $('#createRow input[name="date_of_graduation_of_the_last_grade"]').val();
    var date_of_classification = $('#createRow input[name="date_of_classification"]').val();
    var date_of_graduation = $('#createRow input[name="date_of_graduation"]').val();
    var lp = parseInt( $('#countSchoolYears').val() ) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME,
        data: { date_start: dateStart, date_end: dateEnd, date_of_classification_of_the_last_grade: date_of_classification_of_the_last_grade,
            date_of_graduation_of_the_last_grade: date_of_graduation_of_the_last_grade, date_of_classification: date_of_classification, date_of_graduation: date_of_graduation },
        success: function(id) { refreshRow(id, lp, "add", true); },
        error: function() { refreshRow(0, lp, "add", false); },
    });
}

// -------- formularz modyfikowania: tworzenie formularza, anulowanie i zmiana rekordu --------- //
function clicEditRowButtons() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany danych roku szkolnego.";
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
    var dateStart                                   = $('tr[data-school_year_id='+id+'] input[name="dateStart"]').val();
    var dateEnd                                     = $('tr[data-school_year_id='+id+'] input[name="dateEnd"]').val();
    var date_of_classification_of_the_last_grade    = $('tr[data-school_year_id='+id+'] input[name="date_of_classification_of_the_last_grade"]').val();
    var date_of_graduation_of_the_last_grade        = $('tr[data-school_year_id='+id+'] input[name="date_of_graduation_of_the_last_grade"]').val();
    var date_of_classification                      = $('tr[data-school_year_id='+id+'] input[name="date_of_classification"]').val();
    var date_of_graduation                          = $('tr[data-school_year_id='+id+'] input[name="date_of_graduation"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
        data: { id: id, date_start: dateStart, date_end: dateEnd, date_of_classification_of_the_last_grade: date_of_classification_of_the_last_grade,
            date_of_graduation_of_the_last_grade: date_of_graduation_of_the_last_grade, date_of_classification: date_of_classification, date_of_graduation: date_of_graduation },
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
    clickCreateRowButtons();
    clicEditRowButtons();
    clickDestroyButton();
});