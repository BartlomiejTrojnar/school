// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 15.01.2023 ------------------------ //
// -------------------------- wydarzenia dla widoku nauczyciel/index  --------------------------- //
const FADE_OUT=575, SLIDE_UP=1250, SLIDE_DOWN=1250;
const NUMBER_OF_FIELDS=12, TABLE_NAME="#teachers", DATA_NAME="teacher_id", INPUT_NAME="degree", ROUTE_NAME="nauczyciel";

import '../patternForIndex.js';
import { CreateRowService, RefreshRowService } from '../patternForIndex.js';
import { EditRowService } from '../patternForIndex.js';


// --------------------- odświeżanie tabeli z rekordami po zmianie filtrów --------------------- //
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

// ------------------- odświeżanie wiersza tabeli z informacjami o rekordzie ------------------- //
function refreshRow(id, lp, operation="add", success="true") {   // odświeżenie wiersza z informajami o rekordzie o podanym identyfikatorze
    var RefreshRow = new RefreshRowService(NUMBER_OF_FIELDS, TABLE_NAME, DATA_NAME);
    if(!success)
        switch(operation) {
            case "update": RefreshRow.updateError(id, "Błąd w trakcie modyfikowania nauczyciela."); break;
            case "add": RefreshRow.addError("Błąd w trakcie dodawania nauczyciela."); return;
            case "destroy": RefreshRow.destroyError(id, "Nie można usunąć nauczyciela. Prawdopodobnie istnieją powiązane rekordy."); return;
        }
    if(operation=="destroy")        { RefreshRow.destroySuccess(id); return; }

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { RefreshRow.success(id, result, operation); },
        error: function() { RefreshRow.error(id, operation, "Błąd odświeżania wiersza z nauczycielem."); },
    });
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// --------- formularz dodawania: tworzenie formularza, anulowanie i dodawanie rekordu --------- //
function clickCreateRowButtons() {   // kliknięcie przycisku dodawania
    $('#showCreateRow').click(function(){       // tworzenie formularza dodawania
        $('tr#createRow').remove();
        var communique = "Błąd tworzenia wiersza z formularzem dodawania nauczyciela.";
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
    var last_name       = $('#createRow input[name="last_name"]').val();
    var family_name     = $('#createRow input[name="family_name"]').val();
    var short           = $('#createRow input[name="short"]').val();
    var degree          = $('#createRow input[name="degree"]').val();
    var classroom_id    = $('#createRow select[name="classroom_id"]').val();
    var first_year_id   = $('#createRow select[name="first_year_id"]').val();
    var last_year_id    = $('#createRow select[name="last_year_id"]').val();
    var order           = $('#createRow input[name="order"]').val();
    var lp = parseInt( $('#countTeachers').val() ) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME,
        data: { first_name: first_name, last_name: last_name, family_name: family_name, short: short, degree: degree,
            classroom_id: classroom_id, first_year_id: first_year_id, last_year_id: last_year_id, order: order },
        success: function(id) { refreshRow(id, lp, "add", true); },
        error: function(result) { alert(result); refreshRow(0, lp, "add", false); },
    });
}

// -------- formularz modyfikowania: tworzenie formularza, anulowanie i zmiana rekordu --------- //
function clickEditRowButtons() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany danych nauczyciela.";
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
    var first_name      = $('tr[data-teacher_id="'+id+'"] input[name="first_name"]').val();
    var last_name       = $('tr[data-teacher_id="'+id+'"] input[name="last_name"]').val();
    var family_name     = $('tr[data-teacher_id="'+id+'"] input[name="family_name"]').val();
    var short           = $('tr[data-teacher_id="'+id+'"] input[name="short"]').val();
    var degree          = $('tr[data-teacher_id="'+id+'"] input[name="degree"]').val();
    var classroom_id    = $('tr[data-teacher_id="'+id+'"] select[name="classroom_id"]').val();
    var first_year_id   = $('tr[data-teacher_id="'+id+'"] select[name="first_year_id"]').val();
    var last_year_id    = $('tr[data-teacher_id="'+id+'"] select[name="last_year_id"]').val();
    var order           = $('tr[data-teacher_id="'+id+'"] input[name="order"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
        data: { id: id, first_name: first_name, last_name: last_name, family_name: family_name, short: short, degree: degree,
            classroom_id: classroom_id, first_year_id: first_year_id, last_year_id: last_year_id, order: order },
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
    schoolYearChanged();
    clickCreateRowButtons();
    clickEditRowButtons();
    clickDestroyButton();
});