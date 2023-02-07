// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 07.02.2023 ------------------------ //
// ---------------------------- wydarzenia dla widoku zadanie/info  ---------------------------- //
const SLIDE_UP=750, SLIDE_DOWN=750, FADE_IN=1275, FADE_OUT=750;
const ROUTE_NAME="polecenie", NUMBER_OF_FIELDS = 8, TABLE_NAME="#commands", DATA_NAME="command_id";


// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateRowButton() {
    $('#showCreateRow').click(function(){
        var CreateForm = new Form();
        CreateForm.getCreateForm();   // uruchomienie metody pobierającej formularz
    });
}

// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
function clickShowEditFormButton() {
    $('#commands').delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var EditForm = new Form();
        var id = $(this).data(DATA_NAME);
        var lp = $('tr[data-' +DATA_NAME+ '="'+id+'"]').children(":first").html();
        EditForm.getEditForm(id, lp);
    });
}

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
function clickDestroyButton() {
    $('#commands').delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        var command = new Command(id);
        command.delete();
    });
}

// ------------------------------- pobieranie widoku formularzy -------------------------------- //
class Form {
    getCreateForm() {   // pobieranie widoku formularza dodawania
        $('#showCreateRow').slideUp(SLIDE_UP);
        var CreateRow = new ShowRow();
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/create",
            success: function(result) { CreateRow.showSuccess(result); },
            error: function() {  CreateRow.showError(); },
        });
    }

    getEditForm(id, lp) {   // pobieranie widoku formularza modyfikowania
        var EditRow = new ShowRow();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            data: { id: id, lp: lp },
            success: function(result) { EditRow.showSuccessForEdit(id, result); },
            error: function() { EditRow.showErrorForEdit(id); },
         });
    }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowRow {
    showError() {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania informacji o poleceniu.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $(TABLE_NAME+ ' tr.create').after(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccess(result) {          // udane tworzenie formularza dodawania: wyświetlenie formularza w tabeli
        var inputName = "number";
        $(TABLE_NAME+ ' tr.create').before(result);
        $('#createRow').hide().fadeIn(FADE_IN);
        $('input[name="' +inputName+ '"]').focus();
        this.clickCreateRowButtons();
    }

    clickCreateRowButtons() {  // naciśnięcie przyciku w formularzu dodawania
        $('#createRow button#cancelAdd').click(function() {    // kliknięcie przycisku "anuluj"
            $.when( $('#createRow').fadeOut(FADE_OUT) ).then(function() {
                $('#createRow').remove();
                $('#showCreateRow').slideDown(SLIDE_DOWN);
            });
        });
    
        $('#createRow button#add').click(function() {          // kliknięcie przycisku "dodaj"
            $.when( $('#createRow').fadeOut(FADE_OUT) ).then(function() {
                $('#showCreateRow').slideDown(SLIDE_DOWN);
                var command = new Command();
                command.add();
            });
        });
    }

    showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany informacji o poleceniu.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '="'+id+'"]').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccessForEdit(id, result) {    // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $.when( $('tr[data-' +DATA_NAME+ '="'+id+'"]').fadeOut(FADE_OUT) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="'+id+'"]').replaceWith(result);
            $('tr.editRow[data-' +DATA_NAME+ '="'+id+'"]').hide().fadeIn(FADE_IN);
            $('input[name="number"]').focus();
            ShowRow.clickEditRowButtons();
        });
    }

    static clickEditRowButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        $('.editRow button.cancelUpdate').click(function() {   // kliknięcie przycisku "anuluj"
            var id = $(this).data(DATA_NAME);
            var lp = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="lp"]').val();
            var result = new ShowResultForOperation(id);
            $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then( function() { result.updateSuccess(lp) });
        });

        $('.editRow button.update').click(function() {          // kliknięcie przycisku "zapisz zmiany"
            var id = $(this).data(DATA_NAME);
            var command = new Command(id);
            $.when( $('li[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then( function() { command.update(); });
        });
    }
}

// ---------------------- operacje na rekordach dotyczących rozszerzenia ----------------------- //
class Command {
    constructor(id=0) {
        this.id = id;
    }

    add() {     // wstawienie rekordu do bazy danych
        var task_id     = $('#task_id').val();
        var number      = $('#createRow input[name="number"]').val();
        var command_name= $('#createRow input[name="command"]').val();
        var description = $('#createRow input[name="description"]').val();
        var points      = $('#createRow input[name="points"]').val();
        $('tr#createRow').remove();
        var lp = parseInt($('#countCommands').val());
        var ShowResult = new ShowResultForOperation();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { task_id: task_id, number: number, command: command_name, description: description, points: points },
            success: function(id) { ShowResult.addSuccess(id, lp); },
            error: function() { ShowResult.addError(); },
        });
    }

    update() {  // zapisywanie zmian rekordu
        var id = this.id;
        var task_id     = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="task_id"]').val();
        var number      = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="number"]').val();
        var command_name= $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="command"]').val();
        var description = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="description"]').val();
        var points      = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="points"]').val();
        var lp          = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="lp"]').val();
        var ShowResult = new ShowResultForOperation(id);
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, task_id: task_id, number: number, command: command_name, description: description, points: points },
            success: function() { ShowResult.updateSuccess(lp) },
            error:   function() { ShowResult.updateError() },
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
        var communique = "Nie udało się dodać polecenia.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $(TABLE_NAME+ ' tr.create').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    addSuccess(id, lp) {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        var Insert = new InsertNewCommandToHTML();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: lp+1 },
            success: function(result) { Insert.showSuccess(result); },
            error: function() {  Insert.showError();  },
        });
    }

    updateError() {     // nieudane zapisane zmian wyboru rozszerzenia
        var communique = "Błąd w trakcie modyfikowania polecenia.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '=' +this.id+ ']').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    updateSuccess(lp) {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var updateElement = new UpdateElementInHTML(this.id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: this.id, lp: lp },
            success: function(result) { updateElement.showSuccess(result); },
            error: function() {  updateElement.showError();  },
        });
    }

    destroyError() {    // nieudane usunięcie wyboru rozszerzenia
        var communique = "Błąd w trakcie usuwania polecenia.";
        var error = '<tr data-' +DATA_NAME+ '="' +this.id+ '"><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '=' +this.id+ ']').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    destroySuccess() {  // udane usunięcie wyboru rozszerzenia: usunięcie ze strony informacji o rekordzie
        var id = this.id;
        $.when( $('tr[data-' +DATA_NAME+ '=' +id+ ']').fadeOut(FADE_OUT) )
            .then( function() {  $('tr[data-' +DATA_NAME+ '=' +id+ ']').remove();  });
    }
}

// ------------------- wstawienie informacji o nowym rekordzie do kodu HTML -------------------- //
class InsertNewCommandToHTML {
    showError() {           // nieudane pobranie pola z nowym rozszerzeniem
        var communique = "Nie udało się odświeżyć wiersza z poleceniem. Odśwież stronę by zobaczyć wyniki.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $(TABLE_NAME+ ' tr.create').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccess(result) {   // poprawne pobranie pola z nowym rozszerzeniem - dodanie go do strony
        $('tr.create').before(result);
        $('tr[data-' +DATA_NAME+ '="' +id+ '"]').fadeIn(FADE_IN);
    }
}

// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class UpdateElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        var communique = "Nie udało się odświeżyć wiersza z poleceniem. Odśwież stronę by zobaczyć wyniki.";
        var error = '<tr data-' +DATA_NAME+ '="' +this.id+ '"><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '=' +this.id+ ']').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccess(result) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        var id = this.id;
        $.when( $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').fadeOut(FADE_OUT) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').replaceWith(result);
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide().fadeIn(FADE_IN);
        });
    }
}

function clickError() {     // kliknięcie dowolnego obiektu o klasie .error - usunięcie go ze strony
    $('#commands').delegate('.error', 'click', function() {
        $.when( $(this).slideUp(SLIDE_UP) ).then( function() {
            $(this).remove();
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    clickShowCreateRowButton();
    clickShowEditFormButton();
    clickDestroyButton();
    clickError();
});