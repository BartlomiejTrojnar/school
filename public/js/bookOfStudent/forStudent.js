// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 08.03.2023 ------------------------ //
// ---------------------------------- obsługa numerów ucznia ---------------------------------- //
const SHOW = 1275, HIDE=750;
const NUMBER_OF_FIELDS=6, TABLE_NAME="#studentGradesTable", DATA_NAME="student_number_id", INPUT_NAME="number", ROUTE_NAME="ksiega_uczniow";

clickShowCreateRowButtonForBookOfStudent();
//clickShowEditFormButtonForStudentNumber();
//clickDestroyButtonForStudentNumber();


// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateRowButtonForBookOfStudent() {
    $(TABLE_NAME+' .showCreateFormForBookOfStudent').click(function(){
        var student_grade_id = $(this).parent().data('student_grade_id');
        var CreateForm = new FormForBookOfStudent();
        CreateForm.getCreateForm(student_grade_id);   // uruchomienie metody pobierającej formularz
    });
}
/*
// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
function clickShowEditFormButtonForStudentNumber() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var EditForm = new FormForStudentNumber();
        var id = $(this).data(DATA_NAME);
        EditForm.getEditForm(id);
    });
}

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
function clickDestroyButtonForStudentNumber() {
    $(TABLE_NAME).delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        var SN = new StudentNumber(id);
        SN.delete();
    });
}
*/
// ------------------------------- pobieranie widoku formularzy -------------------------------- //
class FormForBookOfStudent {
    getCreateForm(student_grade_id) {   // pobieranie widoku formularza dodawania
        var student_id = $('input#student_id').val();
        $(TABLE_NAME+' .showCreateRow').hide(HIDE);
        var CreateForm = new ShowFormForBookOfStudent();
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/create",
            data: { version: "forStudent", student_id: student_id },
            success: function(result) { CreateForm.showSuccess(result, student_grade_id); },
            error: function() {  CreateForm.showError(student_grade_id); },
        });
    }

    // getEditForm(id) {   // pobieranie widoku formularza modyfikowania
        // var EditForm = new ShowFormForStudentNumber();
        // $.ajax({
            // type: "GET",
            // headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            // url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            // data: { id: id, version: "forStudent" },
            // success: function(result) { EditForm.showSuccessForEdit(id, result); },
            // error: function() { EditForm.showErrorForEdit(id); },
        //  });
    // }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowFormForBookOfStudent {
    showError(student_grade_id) {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania numeru księgi ucznia.";
        var error = '<tr><td class="error" colspan="'+NUMBER_OF_FIELDS+'">' +communique+ '</tr></tr>';
        $(TABLE_NAME+' tr[data-student_grade_id="' +student_grade_id+ '"]').before(error);
        $('td.error').hide().show(SHOW);
    }

    showSuccess(result, student_grade_id) {   // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $('#createForm').remove();
        $(TABLE_NAME+' tr[data-student_grade_id="' +student_grade_id+ '"]').before(result);
        $('#studentGradeCreateRow').hide().show(SHOW);
        $('input[name="' +INPUT_NAME+ '"]').focus();
        this.clickCreateFormButtons();
    }

    clickCreateFormButtons() {  // naciśnięcie przyciku w formularzu dodawania
        $('aside.createForm button.cancelAdd').click(function() {    // kliknięcie przycisku "anuluj"
            $.when( $('aside.createForm').fadeOut(HIDE) ).then(function() {
                $('aside.createForm').remove();
                $(TABLE_NAME+' .showCreateRow').fadeIn(SHOW);
            });
            return false;
        });
    
        $('aside.createForm button.add').click(function() {          // kliknięcie przycisku "dodaj"
            $.when( $('aside.createForm').fadeOut(HIDE) ).then(function() {
                $(TABLE_NAME+' .showCreateRow').slideDown(SHOW);
                var BS = new BookOfStudent();
                BS.getDataFromForm('aside.createForm');
                BS.add();
            });
            return false;
        });
    }
/*
    showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany numeru dziennika.";
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
            var SN = new StudentNumber(id);
            $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(HIDE) ).then( function() {
                SN.getDataFromForm('tr[data-' +DATA_NAME+ '="' +id+ '"]');
                SN.update();
            });
        });
    }
*/
}

// ---------------------- operacje na rekordach dotyczących historii ucznia ----------------------- //
export class BookOfStudent{
    constructor(id=0) {
        this.id = id;
    }

    getDataFromForm(form) {
        this.student_id = $(form + ' input[name="student_id"]').val();
        this.school_id  = $(form + ' select[name="school_id"]').val();
        this.number     = $(form + ' input[name="number"]').val();
    }

    add() {     // wstawienie rekordu do bazy danych
        var student_id = this.student_id;
        var school_id = this.school_id;
        var number = this.number;
        var ShowResult = new ShowResultForOperation();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, school_id: school_id, number: number, },
            success: function(id) { ShowResult.addSuccess(id, number); },
            error: function() { ShowResult.addError(); },
        });
    }
/*
    update() {  // zapisywanie zmian rekordu
        var id = this.id;
        var student_id = this.student_id;
        var grade_id = this.grade_id;
        var school_year_id = this.school_year_id;
        var number = this.number;
        var confirmationNumber = this.confirmationNumber;
        var ShowResult = new ShowResultForOperation(id);
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, student_id: student_id, grade_id: grade_id, school_year_id: school_year_id, number: number, confirmationNumber: confirmationNumber },
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
*/
}

// ------------------------- pokazywanie wyników operacji na rekordach ------------------------- //
class ShowResultForOperation {
    constructor(id=0) {
        this.id = id;
    }

    addError() {        // nieudane dodanie nowego wyboru rozszerzenia
        $(TABLE_NAME+' tr.create').before('<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">Nie udało się dodać numeru księgi ucznia.</td></tr>');
        $('td.error').hide().show(SHOW);
    }

    addSuccess(id, number) {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        var Insert = new InsertNewBookOfStudentToHTML();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, version: "forStudent" },
            success: function() { Insert.showSuccess(id, number); },
            error: function() {  Insert.showError();  },
        });
    }
/*
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
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').before('<tr><td class="error" colspan="'+NUMBER_OF_FIELDS+'">Nie udało się usunąć numeru ucznia.</td></tr>');
        $('td.error').hide().show(SHOW);
    }

    destroySuccess() {  // udane usunięcie wyboru rozszerzenia: usunięcie ze strony informacji o rekordzie
        var id = this.id;
        $.when( $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide(HIDE) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').remove();
        });
    }
*/
}

// ------------------- wstawienie informacji o nowym rekordzie do kodu HTML -------------------- //
class InsertNewBookOfStudentToHTML {
    showError() {           // nieudane pobranie wiersza z klasą ucznia
        $(TABLE_NAME+' tr:last').before('<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">Nie udało się wyświetlić wiersza z numerem księgi ucznia. Odśwież stronę by zobaczyć wyniki.</td></tr>');
        $('td.error').hide().show(SHOW);
    }

    showSuccess(id, number) {   // poprawne pobranie wiersza z klasą ucznia - dodanie go do strony
        var td = '<td class="bookOfStudent" style="color: #f77; background-color: #228;" data-book_of_student_id="' +id+ '">' + number + '</td>';
        $('.showCreateFormForBookOfStudent').replaceWith(td);
    }
}
/*
// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class UpdateElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').html('<td class="error" colspan="'+NUMBER_OF_FIELDS+'">Nie udało się wyświetlić wiersza z numerem ucznia. Odśwież stronę by zobaczyć wyniki.</td>');
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').show(SHOW);
    }

    showSuccess(result) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').replaceWith(result);
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide().show(SHOW);
    }
}
*/