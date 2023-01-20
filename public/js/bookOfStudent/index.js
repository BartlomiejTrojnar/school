// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.01.2023 ------------------------ //
// ------------------------ wydarzenia dla widoku ksiega_uczniow/index  ------------------------ //
const FADE_OUT=575, SLIDE_UP=1250, SLIDE_DOWN=1250;
const NUMBER_OF_FIELDS=7, TABLE_NAME="#bookOfStudents", DATA_NAME="book_of_student_id", INPUT_NAME="school_id", ROUTE_NAME="ksiega_uczniow";

import '../patternForIndex.js';
import { CreateRowService, RefreshRowService } from '../patternForIndex.js';
import { EditRowService } from '../patternForIndex.js';


// --------------------------- zmiana parametrów filtrowania wpisów ---------------------------- //
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

// ------------------- odświeżanie wiersza tabeli z informacjami o rekordzie ------------------- //
function refreshRow(id, lp, operation="add", success="true") {   // odświeżenie wiersza z informajami o rekordzie o podanym identyfikatorze
    var RefreshRow = new RefreshRowService(NUMBER_OF_FIELDS, TABLE_NAME, DATA_NAME);
    if(!success)
        switch(operation) {
            case "update": RefreshRow.updateError(id, "Błąd w trakcie modyfikowania wpisu w księdze uczniów."); break;
            case "add": RefreshRow.addError("Błąd w trakcie dodawania wpisu w księdze uczniów."); return;
            case "destroy": RefreshRow.destroyError(id, "Nie można usunąć wpisu w księdze uczniów. Prawdopodobnie istnieją powiązane rekordy."); return;
        }
    if(operation=="destroy")        { RefreshRow.destroySuccess(id); return; }

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) { RefreshRow.success(id, result, operation); },
        error: function() { RefreshRow.error(id, operation, "Błąd odświeżania wiersza z wpisem w księdze uczniów."); },
    });
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// --------- formularz dodawania: tworzenie formularza, anulowanie i dodawanie rekordu --------- //
function clickCreateRowButtons() {   // kliknięcie przycisku dodawania
    $('#showCreateRow').click(function(){       // tworzenie formularza dodawania
        $('tr#createRow').remove();
        var communique = "Błąd tworzenia wiersza z formularzem dodawania wpisu księgi uczniów.";
        var CreateRow = new CreateRowService(NUMBER_OF_FIELDS, TABLE_NAME, communique, INPUT_NAME);
        $(TABLE_NAME).animate({width: '75%'}, 500);
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
    var student_id  = $('#createRow select[name="student_id"]').val();
    var school_id   = $('#createRow select[name="school_id"]').val();
    var number      = $('#createRow input[name="number"]').val();
    var lp = parseInt( $('#countRegistrations').val() ) + 1;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME,
        data: { student_id: student_id, school_id: school_id, number: number },
        success: function(id) { refreshRow(id, lp, "add", true); },
        error: function() { refreshRow(0, lp, "add", false); },
    });
}

// -------- formularz modyfikowania: tworzenie formularza, anulowanie i zmiana rekordu --------- //
function clickEditRowButtons() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany danych wpisu w księdze uczniów.";
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
    var student_id  = $('tr[data-book_of_student_id="'+id+'"] select[name="student_id"]').val();
    var school_id   = $('tr[data-book_of_student_id="'+id+'"] select[name="school_id"]').val();
    var number      = $('tr[data-book_of_student_id="'+id+'"] input[name="number"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
        data: { id: id, student_id: student_id, school_id: school_id, number: number },
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
    schoolChanged();
    clickCreateRowButtons();
    clickEditRowButtons();
    clickDestroyButton();
});