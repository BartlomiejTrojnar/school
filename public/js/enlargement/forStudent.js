// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.01.2023 ------------------------ //
// ------------------------- wydarzenia dla widoku uczeń/rozszerzenia  ------------------------- //
const SLIDE_UP=750, SLIDE_DOWN=750, FADE_IN=1275, FADE_OUT=750;
const ROUTE_NAME="rozszerzenie";


// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateFormButton() {
    $('#showCreateForm').click(function(){
        var CreateForm = new Form();
        CreateForm.getCreateForm();   // uruchomienie metody pobierającej formularz
    });
}

// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
function clickShowEditFormButton() {
    $('#enlargementsSection').delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var EditForm = new Form();
        var id = $(this).data('enlargement_id');
        EditForm.getEditForm(id);
    });
}

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
function clickDestroyButton() {
    $('#enlargementsSection').delegate('button.destroy', 'click', function() {
        var id = $(this).data("enlargement_id");
        var enlargement = new Enlargement(id);
        enlargement.delete();
    });
}

// ------------------------------- pobieranie widoku formularzy -------------------------------- //
class Form {
    getCreateForm() {   // pobieranie widoku formularza dodawania
        $('#showCreateForm').slideUp(SLIDE_UP);
        var CreateForm = new ShowForm();
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/create",
            data: { version: "forStudent" },
            success: function(result) { CreateForm.showSuccess(result); },
            error: function() {  CreateForm.showError(); },
        });
    }

    getEditForm(id) {   // pobieranie widoku formularza modyfikowania
        var EditForm = new ShowForm();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            data: { id: id, lp: 0, version: "forStudent" },
            success: function(result) { EditForm.showSuccessForEdit(id, result); },
            error: function() { EditForm.showErrorForEdit(id); },
         });
    }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowForm {
    showError() {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania informacji o wyborze rozszerzenia.";
        var error = '<p class="error">' +communique+ '</p>';
        $('#enlargementsSection').append(error);
        $('p.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccess(result) {   // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $('#createForm').remove();
        $('#enlargementsSection').append(result);
        $('#createForm').hide().fadeIn(FADE_IN);
        $('select[name="subject_id"]').focus();
        this.clickCreateFormButtons();
    }

    clickCreateFormButtons() {  // naciśnięcie przyciku w formularzu dodawania
        $('#createForm button#cancelAdd').click(function() {    // kliknięcie przycisku "anuluj"
            $.when( $('#createForm').fadeOut(FADE_OUT) ).then(function() {
                $('#createForm').remove();
                $('#showCreateForm').slideDown(SLIDE_DOWN);
            });
        });
    
        $('#createForm button#add').click(function() {          // kliknięcie przycisku "dodaj"
            $.when( $('#createForm').fadeOut(FADE_OUT) ).then(function() {
                $('#showCreateForm').slideDown(SLIDE_DOWN);
                var enlargement = new Enlargement();
                enlargement.add();
            });
        });
    }

    showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany informacji o rozszerzeniu.";
        var error = '<li class="error">' +communique+ '</li>';
        $('li[data-enlargement_id="' +id+ '"]').before(error);
        $('li.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccessForEdit(id, result) {    // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $('li[data-enlargement_id="' +id+ '"]').replaceWith(result);
        $('table.editForm').hide().show(FADE_IN);
        $('select[name="subject_id"]').focus();
        this.clickEditFormButtons();
    }

    clickEditFormButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        $('.editForm button.cancelUpdate').click(function() {   // kliknięcie przycisku "anuluj"
            var id = $(this).data("enlargement_id");
            var result = new ShowResultForOperation(id);
            $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { result.updateSuccess() });
        });

        $('.editForm button.update').click(function() {          // kliknięcie przycisku "zapisz zmiany"
            var id = $(this).data("enlargement_id");
            var enlargement = new Enlargement(id);
            $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { enlargement.update(); });
        });
    }
}

// ---------------------- operacje na rekordach dotyczących rozszerzenia ----------------------- //
class Enlargement {
    constructor(id=0) {
        this.id = id;
    }

    add() {     // wstawienie rekordu do bazy danych
        var student_id  = $('#student_id').val();
        var subject_id  = $('#createForm select[name="subject_id"]').val();
        var level       = $('#createForm input[name="level"]').val();
        var choice      = $('#createForm input[name="choice"]').val();
        var resignation = $('#createForm input[name="resignation"]').val();
        var ShowResult = new ShowResultForOperation;
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, subject_id: subject_id, level: level, choice: choice, resignation: resignation },
            success: function(id) { ShowResult.addSuccess(id); },
            error: function() { ShowResult.addError(); },
        });
    }

    update() {  // zapisywanie zmian rekordu
        var id = this.id;
        var student_id  = $('tr[data-enlargement_id="' +id+ '"] input[name="student_id"]').val();
        var subject_id  = $('tr[data-enlargement_id="' +id+ '"] select[name="subject_id"]').val();
        var level       = $('tr[data-enlargement_id="' +id+ '"] input[name="level"]').val();
        var choice      = $('tr[data-enlargement_id="' +id+ '"] input[name="choice"]').val();
        var resignation = $('tr[data-enlargement_id="' +id+ '"] input[name="resignation"]').val();
        var ShowResult = new ShowResultForOperation(id);
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, student_id: student_id, subject_id: subject_id, level: level, choice: choice, resignation: resignation },
            success: function() { ShowResult.updateSuccess() },
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
        $('#showCreateForm').before('<p class="error">Nie udało się dodać wyboru rozszerzenia.</p>');
        $('p.error').hide().slideDown(SLIDE_DOWN);
    }

    addSuccess(id) {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        var Insert = new InsertNewEnlargementToHTML();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: 0, version: "forStudent" },
            success: function(result) { Insert.showSuccess(result); },
            error: function() {  Insert.showError();  },
        });
    }

    updateError() {     // nieudane zapisane zmian wyboru rozszerzenia
        $('li[data-enlargement_id="' +this.id+ '"]').html('Nie udało się zapisać zmian. Odśwież stronę aby zobaczyć poprzedni rekord.');
        $('li[data-enlargement_id="' +this.id+ '"]').addClass('error').show(SLIDE_DOWN);
    }

    updateSuccess() {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var id = this.id;
        var updateElement = new UpdateElementInHTML(id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: 0, version: "forStudent" },
            success: function(result) { updateElement.showSuccess(result); },
            error: function() {  updateElement.showError();  },
        });
    }

    destroyError() {    // nieudane usunięcie wyboru rozszerzenia
        $('li[data-enlargement_id="' +this.id+ '"]').before('<li class="error">Nie udało się usunąć wyboru rozszerzenia.</li>');
        $('li.error').hide().slideDown(SLIDE_DOWN);
    }

    destroySuccess() {  // udane usunięcie wyboru rozszerzenia: usunięcie ze strony informacji o rekordzie
        var id = this.id;
        $.when( $('li[data-enlargement_id="' +this.id+ '"]').slideUp(SLIDE_UP) ).then(function() {
            $('li[data-enlargement_id="' +id+ '"]').remove();
        });
    }
}

// ------------------- wstawienie informacji o nowym rekordzie do kodu HTML -------------------- //
class InsertNewEnlargementToHTML {
    showError() {           // nieudane pobranie pola z nowym rozszerzeniem
        $('#showCreateForm').before('<p class="error">Nie udało się odświeżyć sekcji z wyborami rozszerzeń. Odśwież stronę by zobaczyć wyniki.</p>');
        $('p.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccess(result) {   // poprawne pobranie pola z nowym rozszerzeniem - dodanie go do strony
        $('#showCreateForm').before(result);
    }
}

// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class UpdateElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        $('li[data-enlargement_id="' +this.id+ '"]').html('Nie udało się odświeżyć pola z wyborami rozszerzeń. Odśwież stronę by zobaczyć wyniki.');
        $('li[data-enlargement_id="' +this.id+ '"]').addClass('error').show(SLIDE_DOWN);
    }

    showSuccess(result) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        $('li[data-enlargement_id="' +this.id+ '"]').replaceWith(result);
        $('li[data-enlargement_id="' +this.id+ '"]').hide().show(SLIDE_DOWN);    
    }
}

function clickError() {     // kliknięcie dowolnego obiektu o klasie .error - usunięcie go ze strony
    $('#enlargementsSection').delegate('.error', 'click', function() {
        $.when( $(this).slideUp(SLIDE_UP) ).then( function() {
            $(this).remove();
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    clickShowCreateFormButton();
    clickShowEditFormButton();
    clickDestroyButton();
    clickError();
});