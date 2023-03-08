// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 03.03.2023 ------------------------ //
// ---------------------------------- obsługa historii ucznia ---------------------------------- //
const SHOW = 1275, HIDE=750;
const NUMBER_OF_FIELDS=3, TABLE_NAME="#studentHistory", DATA_NAME="student_history_id", INPUT_NAME="date", ROUTE_NAME="historia_ucznia";

clickShowCreateRowButtonForStudentHistory();
clickShowEditFormButtonForStudentHistory();
clickDestroyButtonForStudentGrade();
clickError();

// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateRowButtonForStudentHistory() {
    $(TABLE_NAME+' .showCreateRow').click(function(){
        var CreateForm = new FormForStudentHistory();
        CreateForm.getCreateForm();   // uruchomienie metody pobierającej formularz
    });
}

// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
function clickShowEditFormButtonForStudentHistory() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var EditForm = new FormForStudentHistory();
        var id = $(this).data(DATA_NAME);
        EditForm.getEditForm(id);
    });
}

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
function clickDestroyButtonForStudentGrade() {
    $(TABLE_NAME).delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        var SH = new StudentHistory(id);
        SH.delete();
    });
}

// ------------------------------- pobieranie widoku formularzy -------------------------------- //
class FormForStudentHistory {
    getCreateForm() {   // pobieranie widoku formularza dodawania
        $(TABLE_NAME+' .showCreateRow').hide(HIDE);
        var CreateForm = new ShowFormForStudentHistory();
        var student_id = $('#student_id').val();
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/create",
            data: { version: "forStudent", student_id: student_id },
            success: function(result) { CreateForm.showSuccess(result); },
            error: function() {  CreateForm.showError(); },
        });
    }

    getEditForm(id) {   // pobieranie widoku formularza modyfikowania
        var EditForm = new ShowFormForStudentHistory();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) { EditForm.showSuccessForEdit(id, result); },
            error: function() { EditForm.showErrorForEdit(id); },
         });
    }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowFormForStudentHistory {
    showError() {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania klasy dla ucznia.";
        var error = '<tr><td class="error" colspan="'+NUMBER_OF_FIELDS+'">' +communique+ '</tr></tr>';
        $(TABLE_NAME+' tr:last').before(error);
        $('td.error').hide().show(SHOW);
    }

    showSuccess(result) {   // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $('#createForm').remove();
        $(TABLE_NAME+' tr:last').before(result);
        $('#studentGradeCreateRow').hide().show(SHOW);
        $('input[name="' +INPUT_NAME+ '"]').focus();
        this.clickCreateFormButtons();
    }

    clickCreateFormButtons() {  // naciśnięcie przyciku w formularzu dodawania
        $(TABLE_NAME+' .createRow button.cancelAdd').click(function() {    // kliknięcie przycisku "anuluj"
            $.when( $(TABLE_NAME+' .createRow').fadeOut(HIDE) ).then(function() {
                $(TABLE_NAME+' .createRow').remove();
                $(TABLE_NAME+' .showCreateRow').fadeIn(SHOW);
            });
        });
    
        $(TABLE_NAME+' .createRow button.add').click(function() {          // kliknięcie przycisku "dodaj"
            $.when( $(TABLE_NAME+' .createRow').fadeOut(HIDE) ).then(function() {
                $(TABLE_NAME+' .showCreateRow').slideDown(SHOW);
                var SH = new StudentHistory();
                SH.getDataFromForm(TABLE_NAME+' tr.createRow');
                SH.add();
            });
        });
    }

    showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany wpisu historii.";
        var error = '<tr><td class="error" colspan="'+NUMBER_OF_FIELDS+'">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '="' +id+ '"]').before(error);
        $('td.error').hide().show(SHOW);
    }

    showSuccessForEdit(id, result) {    // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(HIDE) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').replaceWith(result);
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide().show(SHOW);
            $('input[name="'+INPUT_NAME+'"]').focus();
        });
        this.clickEditFormButtons();
    }

    clickEditFormButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        $(TABLE_NAME).delegate('button.cancelUpdate', 'click', function() {     // kliknięcie przycisku "anuluj" przy modyfikowaniu
            var id = $(this).data(DATA_NAME);
            var result = new ShowResultForOperation(id);
            $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(HIDE) ).then( function() {
                result.updateSuccess(lp);
            });
        });

        $(TABLE_NAME).delegate('button.update', 'click', function() {     // kliknięcie przycisku "anuluj" przy modyfikowaniu
            var id = $(this).data(DATA_NAME);
            var SH = new StudentHistory(id);
            $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(HIDE) ).then( function() {
                SH.getDataFromForm('tr[data-' +DATA_NAME+ '="' +id+ '"]');
                SH.update();
            });
        });
    }
}

// ---------------------- operacje na rekordach dotyczących historii ucznia ----------------------- //
export class StudentHistory{
    constructor(id=0) {
        this.id = id;
    }

    getData(student_id, date, event=0, confirmationDate=0, confirmationEvent=0) {
        this.student_id = student_id;
        this.date = date;
        this.event = event;
        this.confirmationDate = confirmationDate;
        this.confirmationEvent = confirmationEvent;
    }

    getDataFromForm(form) {
        this.student_id         = $(form + ' input[name="student_id"]').val();
        this.date               = $(form + ' input[name="date"]').val();
        this.event              = $(form + ' input[name="event"]').val();
        this.confirmationDate   = $(form + ' input[name="confirmation_date"]').is(":checked");
        this.confirmationEvent  = $(form + ' input[name="confirmation_event"]').is(":checked");
        if(this.confirmationDate)   this.confirmationDate=1;    else this.confirmationDate=0;
        if(this.confirmationEvent)  this.confirmationEvent=1;   else this.confirmationEvent=0;
    }

    add() {     // wstawienie rekordu do bazy danych
        var student_id = this.student_id;
        var date = this.date;
        var event = this.event;
        var confirmationDate = this.confirmationDate;
        var confirmationEvent = this.confirmationEvent;
        var ShowResult = new ShowResultForOperation();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, date: date, event: event, confirmation_date: confirmationDate, confirmation_event: confirmationEvent },
            success: function(id) { ShowResult.addSuccess(id); },
            error: function() { ShowResult.addError(); },
        });
    }

    update() {  // zapisywanie zmian rekordu
        var id = this.id;
        var student_id = this.student_id;
        var date = this.date;
        var event = this.event;
        var confirmationDate = this.confirmationDate;
        var confirmationEvent = this.confirmationEvent;
        var ShowResult = new ShowResultForOperation(id);
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, student_id: student_id, date: date, event: event, confirmation_date: confirmationDate, confirmation_event: confirmationEvent },
            success: function() { ShowResult.updateSuccess(); },
            error:   function() { ShowResult.updateError(); },
        });
    }

    delete() {  // usuwanie rekordu
        var id = this.id;
        var ShowResult = new ShowResultForOperation(id);
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/" + id,
            success: function() { ShowResult.destroySuccess(); },
            error:   function() { ShowResult.destroyError(); }
        });
    }
}

// ------------------------- pokazywanie wyników operacji na rekordach ------------------------- //
class ShowResultForOperation {
    constructor(id=0) {
        this.id = id;
    }

    addError() {        // nieudane dodanie nowego wyboru rozszerzenia
        $(TABLE_NAME+' tr.create').before('<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">Nie udało się dodać wpisu historii dla ucznia.</td></tr>');
        $('td.error').hide().slideDown(SHOW);
    }

    addSuccess(id) {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        var Insert = new InsertNewStudentHistoryToHTML();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, version: "forStudent" },
            success: function(result) { Insert.showSuccess(result, id); },
            error: function() {  Insert.showError();  },
        });
    }

    updateError() {     // nieudane zapisane zmian wyboru rozszerzenia
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').html('<td colspan="'+NUMBER_OF_FIELDS+'" class="error">Nie udało się zapisać zmian. Odśwież stronę aby zobaczyć poprzedni rekord.</td>');
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide().show(SHOW);
    }

    updateSuccess() {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var id = this.id;
        var updateElement = new UpdateElementInHTML(id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, version: "forStudent" },
            success: function(result) { updateElement.showSuccess(result); },
            error: function() {  updateElement.showError();  },
        });
    }

    destroyError() {    // nieudane usunięcie wyboru rozszerzenia
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').before('<tr><td class="error" colspan="'+NUMBER_OF_FIELDS+'">Nie udało się usunąć wpisu historii ucznia.</td></tr>');
        $('td.error').hide().show(SHOW);
    }

    destroySuccess() {  // udane usunięcie wyboru rozszerzenia: usunięcie ze strony informacji o rekordzie
        var id = this.id;
        $.when( $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide(HIDE) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').remove();
        });
    }
}

// ------------------- wstawienie informacji o nowym rekordzie do kodu HTML -------------------- //
class InsertNewStudentHistoryToHTML {
    showError() {           // nieudane pobranie wiersza z klasą ucznia
        $(TABLE_NAME+' tr:last').before('<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">Nie udało się wyświetlić wiersza z klasą ucznia. Odśwież stronę by zobaczyć wyniki.</td></tr>');
        $('td.error').hide().show(SHOW);
    }

    showSuccess(result, id) {   // poprawne pobranie wiersza z klasą ucznia - dodanie go do strony
        $(TABLE_NAME+' tr.create').before(result);
        $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide().show(SHOW);
    }
}

// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class UpdateElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').html('<td class="error" colspan="'+NUMBER_OF_FIELDS+'">Nie udało się wyświetlić wiersza z historią ucznia. Odśwież stronę by zobaczyć wyniki.</td>');
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').show(SHOW);
    }

    showSuccess(result) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').replaceWith(result);
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide().show(SHOW);
    }
}

function clickError() {     // kliknięcie dowolnego obiektu o klasie .error - usunięcie go ze strony
    $(TABLE_NAME).delegate('.error', 'click', function() {
        $.when( $(this).hide(HIDE) ).then( function() {
            $(this).remove();
        });
    });
}