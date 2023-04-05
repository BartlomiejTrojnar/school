// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 05.04.2022 ------------------------ //
// --------------------- wydarzenia na stronie wyświetlania grup dla klasy --------------------- //
const FADE_OUT=575, SLIDE_UP=1250, SLIDE_DOWN=1250;
const NUMBER_OF_FIELDS=12, TABLE_NAME="#groups", DATA_NAME="group_id", INPUT_NAME="comments", ROUTE_NAME="grupa";

import '../patternForIndex.js';
import { CreateRowService, RefreshRowService } from '../patternForIndex.js';
import { EditRowService } from '../patternForIndex.js';


// ------------------- odświeżanie wiersza tabeli z informacjami o rekordzie ------------------- //
function refreshRow(id, lp, operation="add", success="true") {   // odświeżenie wiersza z informajami o rekordzie o podanym identyfikatorze
    var RefreshRow = new RefreshRowService(NUMBER_OF_FIELDS, TABLE_NAME, DATA_NAME);
    if(!success)
        switch(operation) {
            case "update": RefreshRow.updateError(id, "Nie udało się zapisać zmian."); break;
            case "add": RefreshRow.addError("Błąd: nie udało się dodać grupy."); return;
            case "destroy": RefreshRow.destroyError(id, "Nie można usunąć grupy. Prawdopodobnie istnieją powiązane rekordy."); return;
        }
    if(operation=="destroy")        { RefreshRow.destroySuccess(id); return; }

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
        data: { id: id, lp: lp, version: "forIndex" },
        success: function(result) { RefreshRow.refreshSuccess(id, operation, result); },
        error: function() { RefreshRow.refreshError(id, operation, "Błąd odświeżania wiersza z grupą."); },
    });
}

// -------------------------- operacje na rekordach dotyczących grupy -------------------------- //
class Group{
    constructor(id=0) {
        this.id = id;
    }

    getData(student_id, date, event=0, confirmationDate=0, confirmationEvent=0) {
        alert("Nie działam - linia 47");
        return;
        this.student_id = student_id;
        this.date = date;
        this.event = event;
        this.confirmationDate = confirmationDate;
        this.confirmationEvent = confirmationEvent;
    }

    getDataFromForm(form) {
        this.subject_id = $(form + ' select[name="subject_id"]').val();
        this.level      = $(form + ' select[name="level"]').val();
        this.comments   = $(form + ' input[name="comments"]').val();
        this.start      = $(form + ' input[name="start"]').val();
        this.end        = $(form + ' input[name="end"]').val();
        this.hours      = $(form + ' input[name="hours"]').val();
    }

    add() {     // wstawienie rekordu do bazy danych
        var subject_id = this.subject_id;
        var level = this.level;
        var comments = this.comments;
        var start = this.start;
        var end = this.end;
        var hours = this.hours;
        var lp = $('#groups tr').length-3;
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { subject_id: subject_id, level: level, comments: comments, start: start, end: end, hours: hours },
            success: function(id) {
                refreshRow(id, lp, "add", true);
                var url = 'http://localhost/school/grupa_nauczyciele/addTeacher/'+id;
                alert(url);
                $(location).attr("href", url);
            },
            error: function() { refreshRow(0, 0, "add", false); },
        });
    }

    update(lp) {  // zapisywanie zmian rekordu
        var id = this.id;
        var subject_id  = this.subject_id;
        var level       = this.level;
        var comments    = this.comments;
        var start       = this.start;
        var end         = this.end;
        var hours       = this.hours;
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, subject_id: subject_id, level: level, comments: comments, start: start, end: end, hours: hours },
            success: function() { refreshRow(id, lp, "update", true); },
            error:   function() { refreshRow(id, lp, "update", false); },
        });
    }

    delete() {  // usuwanie rekordu
        var id = this.id;
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/" + id,
            success: function() { refreshRow(id, 0, "destroy", true); },
            error:   function() { refreshRow(id, 0, "destroy", false); }
        });
    }
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// --------- formularz dodawania: tworzenie formularza, anulowanie i dodawanie rekordu --------- //
function clickCreateRowButtons() {   // kliknięcie przycisku dodawania
    $('#showCreateRow').click(function(){       // tworzenie formularza dodawania
        $('tr#createRow').remove();
        var communique = "Błąd tworzenia wiersza z formularzem dodawania grupy.";
        var CreateRow = new CreateRowService(NUMBER_OF_FIELDS, TABLE_NAME, communique, INPUT_NAME);
        $(TABLE_NAME).animate({width: '100%'}, 500);
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
            var group = new Group();
            group.getDataFromForm(TABLE_NAME+' tr#createRow');
            group.add();
        });
    });
}

// -------- formularz modyfikowania: tworzenie formularza, anulowanie i zmiana rekordu --------- //
function clickEditRowButtons() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany danych grupy.";
        var EditRow = new EditRowService(NUMBER_OF_FIELDS, DATA_NAME, communique, INPUT_NAME);
        var id = $(this).data(DATA_NAME);
        var lp = parseInt( $('tr[data-' +DATA_NAME+ '="'+id+'"]').children('td:first').children().html() );
        var version = $(this).data('version');
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
        var group = new Group(id);
        $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then( function() {
            group.getDataFromForm('tr[data-' +DATA_NAME+ '="' +id+ '"]');
            group.update(lp);
        });
});
}

// ------------------------------------- usuwanie rekordu -------------------------------------- //
function clickDestroyButton() {
    $(TABLE_NAME).delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        var group = new Group(id);
        group.delete();
    });
}

// ------------------------------ wybór klasy w polu select ------------------------------------ //
function gradeChanged() {
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/klasa/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór przedmiotu w polu select ------------------------------- //
function subjectChanged() {
    $('select[name="subject_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { window.location.reload(); },
        });
        return false;
    });
}
// --------------------------- wybór poziomu egzaminu w polu select ---------------------------- //
function levelChanged() {
    $('select[name="level"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/level/change/" + $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór nauczyciela w polu select ------------------------------ //
function teacherChanged() {
    $('select[name="teacher_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/nauczyciel/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
            error: function() { alert('Błąd przy zmianie nauczyciela'); window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór nauczyciela w polu select ------------------------------ //
function schoolYearChanged() {
    $('select[name="schoolYear"]').bind('change', function(){
        var year = 1900 + parseInt($(this).val());
        start = (year-1)+'-09-01';
        end = year+'-08-31';
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() { $('#start').val(start); $('#end').val(end); rememberDates(start, end); },
            error: function() { alert('Błąd przy zmianie roku szkolnego'); window.location.reload(); },
        });
        return false;
    });
}

// po zmianie daty początkowej - sprawdzenie dat grupy
function dateStartChange() {
    $('input[name="start"]').bind('blur', function(){
        start = $(this).val();
        end = $('input[name="end"]').val();
        rememberDates(start, end);
        return false;
    });
}
// po zmianie daty końcowej - sprawdzenie dat grupy
function dateEndChange() {
    $('input[name="end"]').bind('blur', function(){
        start = $('input[name="start"]').val();
        end = $(this).val();
        rememberDates(start, end);
        return false;
    });
}
//zapamiętanie dat w sesji
function rememberDates(start, end) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/rememberDates",
        data: { dateView: start, end: end },
        success: function() { window.location.reload(); },
        error: function() { 
            alert('Błąd');
            //window.location.reload();
         },
    });
}

function clickError() {     // kliknięcie dowolnego obiektu o klasie .error - usunięcie go ze strony
    $(TABLE_NAME).delegate('.error', 'click', function() {
        $.when( $(this).hide(FADE_OUT) ).then( function() {
            $(this).remove();
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChanged();
    subjectChanged();
    levelChanged();
    teacherChanged();
    schoolYearChanged();
    dateStartChange();
    dateEndChange();

    clickCreateRowButtons();
    clickEditRowButtons();
    clickDestroyButton();
    clickError();
});