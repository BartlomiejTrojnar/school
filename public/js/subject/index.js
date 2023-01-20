// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ------------------------ //
// -------------------------- wydarzenia dla widoku przedmiot/index  --------------------------- //
const FADE_OUT=575, FADE_IN=5275, SLIDE_UP=1250, SLIDE_DOWN=1250;
const NUMBER_OF_FIELDS=7, TABLE_NAME="#subjects", DATA_NAME="subject_id", INPUT_NAME="name";

import '../patternForIndex.js';
import { CreateRowService, RefreshRowService } from '../patternForIndex.js';
import { EditRowService } from '../patternForIndex.js';


// ------------------- odświeżanie wiersza tabeli z informacjami o rekordzie ------------------- //
function refreshRow(id, lp, operation="add", success="true") {   // odświeżenie wiersza z informajami o rekordzie o podanym identyfikatorze
    var RefreshRow = new RefreshRowService(NUMBER_OF_FIELDS, TABLE_NAME, DATA_NAME);
    if(!success)
        switch(operation) {
            case "update": RefreshRow.updateError(id, "Błąd w trakcie modyfikowania przedmiotu."); break;
            case "add": RefreshRow.addError("Błąd w trakcie dodawania przedmiotu."); return;
            case "destroy": RefreshRow.destroyError(id, "Nie można usunąć przedmiotu. Prawdopodobnie istnieją powiązane rekordy."); return;
        }
    if(operation=="destroy")        { RefreshRow.destroySuccess(id); return; }

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { RefreshRow.success(id, result, operation); },
        error: function() { RefreshRow.error(id, operation, "Błąd odświeżania wiersza z przedmiotem."); },
    });
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// --------- formularz dodawania: tworzenie formularza, anulowanie i dodawanie rekordu --------- //
function clickCreateRowButtons() {   // kliknięcie przycisku dodawania
    $('#showCreateRow').click(function(){       // tworzenie formularza dodawania
        $('tr#createRow').remove();
        var communique = "Błąd tworzenia wiersza z formularzem dodawania przedmiotu.";
        var inputName = "name";
        var CreateRow = new CreateRowService(NUMBER_OF_FIELDS, TABLE_NAME, communique, inputName);
        $(TABLE_NAME).animate({width: '70%'}, 500);
        $.when( $(this).slideUp(SLIDE_UP) ).then(function() { CreateRow.show("przedmiot"); });
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
    var name        = $('#createRow input[name="name"]').val();
    var short_name  = $('#createRow input[name="short_name"]').val();
    var actual      = $('#createRow input[name="actual"]').prop('checked');
    var order_in_the_sheet = $('#createRow input[name="order_in_the_sheet"]').val();
    var expanded = $('#createRow input[name="expanded"]').prop('checked');
    var lp = parseInt( $('#countSubjects').val() ) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot",
        data: { name: name, short_name: short_name, actual: actual, order_in_the_sheet: order_in_the_sheet, expanded: expanded },
        success: function(id) { refreshRow(id, lp, "add", true); },
        error: function() { refreshRow(0, lp, "add", false); },
    });
}


// -------- formularz modyfikowania: tworzenie formularza, anulowanie i zmiana rekordu --------- //
function clicEditRowButtons() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany danych przedmiotu.";
        var EditRow = new EditRowService(NUMBER_OF_FIELDS, DATA_NAME, communique, INPUT_NAME);
        var id = $(this).data(DATA_NAME);
        var lp = $('tr[data-' +DATA_NAME+ '="'+id+'"]').children(":first").html();
        EditRow.show("przedmiot", id, lp);
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
    var name                = $('tr[data-subject_id='+id+'] input[name="name"]').val();
    var short_name          = $('tr[data-subject_id='+id+'] input[name="short_name"]').val();
    var actual              = $('tr[data-subject_id='+id+'] input[name="actual"]').prop('checked');
    var order_in_the_sheet  = $('tr[data-subject_id='+id+'] input[name="order_in_the_sheet"]').val();
    var expanded            = $('tr[data-subject_id='+id+'] input[name="expanded"]').prop('checked');

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/przedmiot/"+id,
        data: { id: id, name: name, short_name: short_name, actual: actual, order_in_the_sheet: order_in_the_sheet, expanded: expanded },
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
            url: "http://localhost/school/przedmiot/" + id,
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