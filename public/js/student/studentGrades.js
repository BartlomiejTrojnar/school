// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 25.02.2022 ------------------------ //
// ---------------------- wydarzenia na stronie wyświetlania klas ucznia ----------------------- //
const SLIDE_UP=750, SLIDE_DOWN=750, FADE_IN=1275, FADE_OUT=750;
const TABLE_NAME="#studentGradesTable", ROUTE_NAME="klasy_ucznia", DATA_NAME="student_grade_id";

import '../studentHistory/forStudent.js';
import { StudentHistory } from '../studentHistory/forStudent.js';
import '../studentNumber/forStudent.js';

// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateRowButtonForStudentGrade() {
    $(TABLE_NAME+' .showCreateRow').click(function(){
        var CreateForm = new FormForStudentGrade();
        CreateForm.getCreateForm();   // uruchomienie metody pobierającej formularz
    });
}
/*
// --------------- kliknięcie przycisku uruchamiającego formularz zamiany (dodania nowego) rozszerzenia ---------------- //
function clickShowExchangeFormButton() {
    $('#enlargementsSection').delegate('button.exchange', 'click', function() {     // tworzenie formularza modyfikowania
        var ExchangeForm = new Form();
        var id = $(this).data('enlargement_id');
        ExchangeForm.getExchangeForm(id);
    });
}
*/
/*
// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
function clickShowEditFormButtonForStudentGrade() {
    $(TABLE_NAME).delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var EditForm = new FormForStudentGrade();
        var id = $(this).data(DATA_NAME);
        EditForm.getEditForm(id);
    });
}

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
function clickDestroyButtonForStudentGrade() {
    $(TABLE_NAME).delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        var studentGrade = new StudentGrade(id);
        studentGrade.delete();
    });
}
*/
// ------------------------------- pobieranie widoku formularzy -------------------------------- //
class FormForStudentGrade {
    getCreateForm() {   // pobieranie widoku formularza dodawania
        $(TABLE_NAME+' .showCreateRow').slideUp(SLIDE_UP);
        var CreateForm = new ShowFormForStudentGrade();
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

    // getExchangeForm(id) {   // pobieranie widoku formularza zamiany
        // var ExchangeForm = new ShowForm();
        // $.ajax({
            // type: "GET",
            // headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            // url: "http://localhost/school/" +ROUTE_NAME+ "/exchange/"+id,
            // data: { id: id },
            // success: function(result) { ExchangeForm.showSuccessForExchange(id, result); },
            // error: function() { ExchangeForm.showErrorForExchange(id); },
        //  });
    // }

    getEditForm(id) {   // pobieranie widoku formularza modyfikowania
        var EditForm = new ShowFormForStudentGrade();
        var lp = parseInt( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').children(":first").html() );
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            data: { id: id, lp: lp, version: "forStudent" },
            success: function(result) { EditForm.showSuccessForEdit(id, result); },
            error: function() { EditForm.showErrorForEdit(id); },
         });
    }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowFormForStudentGrade {
    showError() {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania klasy dla ucznia.";
        var error = '<tr><td class="error" colspan="6">' +communique+ '</tr></tr>';
        $(TABLE_NAME).append(error);
        $('td.error').hide().show(FADE_IN);
    }

    showSuccess(result) {   // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $('#createForm').remove();
        $(TABLE_NAME).append(result);
        $('#studentGradeCreateRow').hide().fadeIn(FADE_IN);
        $('#studentGradeProposedDates').hide().fadeIn(FADE_IN);
        $('input[name="start"]').focus();
        this.clickCreateFormButtons();
    }

    clickCreateFormButtons() {  // naciśnięcie przyciku w formularzu dodawania
        $('#studentGradeCreateRow button.cancelAdd').click(function() {    // kliknięcie przycisku "anuluj"
            $('#studentGradeProposedDates').remove();
            $.when( $('#studentGradeCreateRow').fadeOut(FADE_OUT) ).then(function() {
                $('#studentGradeCreateRow').remove();
                $(TABLE_NAME+' .showCreateRow').slideDown(SLIDE_DOWN);
            });
        });
    
        $('#studentGradeCreateRow button.add').click(function() {          // kliknięcie przycisku "dodaj"
            $('#studentGradeProposedDates').remove();
            $.when( $('#studentGradeCreateRow').fadeOut(FADE_OUT) ).then(function() {
                $(TABLE_NAME+' .showCreateRow').slideDown(SLIDE_DOWN);
                var studentGrade = new StudentGrade();
                studentGrade.add();
            });
        });
    }

/*
    showErrorForExchange(id) {      // nieudane pobranie widoku formularza zamiany
        var communique = "Nie mogę utworzyć formularza do zamiany rozszerzenia.";
        var error = '<li class="error">' +communique+ '</li>';
        $('li[data-enlargement_id="' +id+ '"]').before(error);
        $('li.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccessForExchange(id, result) {    // udane pobranie widoku formularza zamiany: pokazanie formularza na stronie
        $('li[data-enlargement_id="' +id+ '"]').replaceWith(result);
        $('table.editForm').hide().show(FADE_IN);
        $('select[name="subject_id"]').focus();
        this.clickExchangeFormButtons();
    }

    clickExchangeFormButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        $('.exchangeForm button.cancelUpdate').click(function() {   // kliknięcie przycisku "anuluj"
            var id = $(this).data("enlargement_id");
            var result = new ShowResultForOperation(id);
            $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { result.exchangeSuccess() });
        });

        $('.exchangeForm button.update').click(function() {          // kliknięcie przycisku "zapisz zmiany"
            var id = $(this).data("enlargement_id");
            var enlargement = new Enlargement(id);
            $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { enlargement.exchange(); });
        });
    }
*/
/*
    showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany informacji o rozszerzeniu.";
        var error = '<tr><td class="error" colspan="6">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '="' +id+ '"]').before(error);
        $('td.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccessForEdit(id, result) {    // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').replaceWith(result);
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide().show(FADE_IN);
            $('input[name="start"]').focus();
        });
        this.clickEditFormButtons();
    }

    clickEditFormButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        $(TABLE_NAME).delegate('button.cancelUpdate', 'click', function() {     // kliknięcie przycisku "anuluj" przy modyfikowaniu
            var id = $(this).data(DATA_NAME);
            var lp = $('input[name="lp"]').val()
            var result = new ShowResultForOperation(id);
            $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then( function() {
                $('tr[data-' +DATA_NAME+ '="' +id+ '"].proposedDates').remove();
                result.updateSuccess(lp);
            });
        });

        $(TABLE_NAME).delegate('button.update', 'click', function() {     // kliknięcie przycisku "anuluj" przy modyfikowaniu
            var id = $(this).data(DATA_NAME);
            var studentGrade = new StudentGrade(id);
            $.when( $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then( function() { studentGrade.update(); });
        });
    }
*/
}

// ---------------------- operacje na rekordach dotyczących rozszerzenia ----------------------- //
class StudentGrade {
    constructor(id=0) {
        this.id = id;
    }

    add() {     // wstawienie rekordu do bazy danych
        var student_id = $('#student_id').val();
        var grade_id = $('#studentGradeCreateRow select[name="grade_id"]').val();
        var start = $('#studentGradeCreateRow input[name="start"]').val();
        var end   = $('#studentGradeCreateRow input[name="end"]').val();
        var confirmationStart = $('#studentGradeCreateRow input[name="confirmationStart"]').is(":checked");
        var confirmationEnd = $('#studentGradeCreateRow input[name="confirmationEnd"]').is(":checked");
        if(confirmationStart)   confirmationStart=1; else confirmationStart=0;
        if(confirmationEnd)     confirmationEnd=1;   else confirmationEnd=0;
        var ShowResult = new ShowResultForOperation();
        ShowResult.addSuccess(9287);
        return;
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, grade_id: grade_id, start: start, end: end, confirmation_start: confirmationStart, confirmation_end: confirmationEnd },
            success: function(id) { ShowResult.addSuccess(id); },
            error: function() { ShowResult.addError(); },
        });
    }
/*
    exchange() {  // zamiana rozszerzenia
        var id = this.id;
        var student_id  = $('tr[data-enlargement_id="' +id+ '"] input[name="student_id"]').val();
        var subject_id  = $('tr[data-enlargement_id="' +id+ '"] select[name="subject_id"]').val();
        var level       = $('tr[data-enlargement_id="' +id+ '"] input[name="level"]').val();
        var choice      = $('tr[data-enlargement_id="' +id+ '"] input[name="choice"]').val();
        var resignation = $('tr[data-enlargement_id="' +id+ '"] input[name="resignation"]').val();
        var ShowResult = new ShowResultForOperation(id);
        // dodanie nowego rozszerzenia
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, subject_id: subject_id, level: level, choice: choice, resignation: resignation },
            success: function(id) { ShowResult.addSuccess(id, choice); },
            error: function() { ShowResult.addError(); },
        });
        // zmiana daty końcowej starego rozszerzenia
        var day = parseInt(choice.substr(8,2));
        var mon = parseInt(choice.substr(5,2))-1;
        var year = parseInt(choice.substr(0,4));
        var oldResignation = new Date(year, mon, day);
        oldResignation.setDate(oldResignation.getDate() - 1);
        day = oldResignation.getDate();  mon=oldResignation.getMonth()+1; year = oldResignation.getFullYear();
        resignation = year + '-' + mon + '-' + day;
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/exchangeUpdate/"+id,
            data: { id: id, resignation: resignation },
            success: function() { ShowResult.exchangeSuccess(); },
            error:   function() { ShowResult.exchangeError() },
        });
    }
*/
/*
    update() {  // zapisywanie zmian rekordu
        var id = this.id;
        var student_id  = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="student_id"]').val();
        var grade_id    = $('tr[data-' +DATA_NAME+ '="' +id+ '"] select[name="grade_id"]').val();
        var start       = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="start"]').val();
        var end         = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="end"]').val();
        var confirmationStart   = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="confirmationStart"]').is(":checked");
        var confirmationEnd     = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="confirmationEnd"]').is(":checked");
        if(confirmationStart)   confirmationStart=1; else confirmationStart=0;
        if(confirmationEnd)     confirmationEnd=1;   else confirmationEnd=0;
        var lp = $('tr[data-' +DATA_NAME+ '="' +id+ '"] input[name="lp"]').val();
        var ShowResult = new ShowResultForOperation(id);
        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, student_id: student_id, grade_id: grade_id, start: start, end: end, confirmation_start: confirmationStart, confirmation_end: confirmationEnd },
            success: function() { ShowResult.updateSuccess(lp); },
            error:   function() { ShowResult.updateError(lp); },
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
        $(TABLE_NAME+' tr.create').before('<tr><td colspan="6" class="error">Nie udało się dodać klasy dla ucznia.</td></tr>');
        $('td.error').hide().slideDown(SLIDE_DOWN);
    }

    addSuccess() {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        // var Insert = new InsertNewStudentGradeToHTML();
        // var lp = $('#studentGradesTable tr').length-2;
        // var id = this.id;
        // $.ajax({
            // method: "POST",
            // headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            // url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            // data: { id: id, lp: lp, version: "forStudent" },
            // success: function(result) { Insert.showSuccess(result, id); },
            // error: function() {  Insert.showError();  },
        // });


        var SH = new StudentHistory();
        // this.event = $('#studentGradeCreateRow input[name="start"]').val();
        // this.confirmationDate = $('#studentGradeCreateRow input[name="confirmationStart"]').is(":checked");
        // this.confirmationEvent = $('#studentGradeCreateRow input[name="confirmationEnd"]').is(":checked");
        var student_id = $('#student_id').val();
        var start = $('#studentGradeCreateRow input[name="start"]').val();
        var event = "przyjęto do klasy";
        SH.getData(student_id, start, event, 1, 0);
        SH.add();
        // alert(309);

    }
/*
    exchangeError() {     // nieudane zapisane zamiany wyboru rozszerzenia
        $('li[data-enlargement_id="' +this.id+ '"]').html('Nie udało się zapisać zmian. Odśwież stronę aby zobaczyć poprzedni rekord.');
        $('li[data-enlargement_id="' +this.id+ '"]').addClass('error').show(SLIDE_DOWN);
    }

    exchangeSuccess() {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var id = this.id;
        var exchangeElement = new ExchangeElementInHTML(id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: 0, version: "forStudent" },
            success: function(result) { exchangeElement.showSuccess(result); },
            error: function() {  exchangeElement.showError();  },
        });
    }
*/
/*
    updateError() {     // nieudane zapisane zmian wyboru rozszerzenia
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"].proposedDates').remove();
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').html('<td colspan="6" class="error">Nie udało się zapisać zmian. Odśwież stronę aby zobaczyć poprzedni rekord.</td>');
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide().show(SLIDE_DOWN);
    }

    updateSuccess(lp) {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var id = this.id;
        var updateElement = new UpdateElementInHTML(id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: lp, version: "forStudent" },
            success: function(result) { updateElement.showSuccess(result); },
            error: function() {  updateElement.showError();  },
        });
    }

    destroyError() {    // nieudane usunięcie wyboru rozszerzenia
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').before('<tr><td class="error" colspan="6">Nie udało się usunąć klasy ucznia.</td></tr>');
        $('td.error').hide().slideDown(SLIDE_DOWN);
    }

    destroySuccess() {  // udane usunięcie wyboru rozszerzenia: usunięcie ze strony informacji o rekordzie
        var id = this.id;
        $.when( $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide(SLIDE_UP) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="' +id+ '"]').remove();
        });
    }
*/
}

// ------------------- wstawienie informacji o nowym rekordzie do kodu HTML -------------------- //
class InsertNewStudentGradeToHTML {
    showError() {           // nieudane pobranie wiersza z klasą ucznia
        $(TABLE_NAME+' .showCreateRow').before('<tr><td colspan="6" class="error">Nie udało się wyświetlić wiersza z klasą ucznia. Odśwież stronę by zobaczyć wyniki.</td></tr>');
        $('td.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccess(result, id) {   // poprawne pobranie wiersza z klasą ucznia - dodanie go do strony
        $(TABLE_NAME+' tr.create').before(result);
        $('tr[data-' +DATA_NAME+ '="' +id+ '"]').hide().slideDown(SLIDE_DOWN);
    }
}

/*
// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class ExchangeElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        $('li[data-enlargement_id="' +this.id+ '"]').html('Nie udało się odświeżyć pola z wyborami rozszerzeń. Odśwież stronę by zobaczyć wyniki.');
        $('li[data-enlargement_id="' +this.id+ '"]').addClass('error').show(SLIDE_DOWN);
    }

    showSuccess(result) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        $('li[data-enlargement_id="' +this.id+ '"]').replaceWith(result);
        $('li[data-enlargement_id="' +this.id+ '"]').hide().slideDown(SLIDE_DOWN);
    }
}
*/

// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class UpdateElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').html('<td class="error" colspan="6">Nie udało się wyświetlić wiersza z klasą ucznia. Odśwież stronę by zobaczyć wyniki.</td>');
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').show(SLIDE_DOWN);
    }

    showSuccess(result) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"].proposedDates').remove();
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').replaceWith(result);
        $('tr[data-' +DATA_NAME+ '="' +this.id+ '"]').hide().slideDown(SLIDE_DOWN);
    }
}

function clickError() {     // kliknięcie dowolnego obiektu o klasie .error - usunięcie go ze strony
    $(TABLE_NAME).delegate('.error', 'click', function() {
        $.when( $(this).slideUp(SLIDE_UP) ).then( function() {
            $(this).remove();
        });
    });
}






/*

// --------------------------------------- numery ucznia --------------------------------------- //




// ---------------------------- zarządzanie numerami księgi uczniów ---------------------------- //
function showCreateFormForBookOfStudentClick() {
    $('.showCreateFormForBookOfStudent button').click(function(){
        var student_id = $('input#student_id').val();
        showCreateFormForBookOfStudent(student_id);
        return false;
    });
}

function showCreateFormForBookOfStudent(student_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow/create",
        data: { version: "forStudent" },
        success: function(result) {
            $('aside.createForm').replaceWith(result);
            addBookOfStudentClick(student_id);
        },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Nie można utworzyć formularza dla modyfikowania numeru księgi ucznia!</td></tr>';
            $("#studentGradesTable tr.create").before(error);
        },
    });
}

function addBookOfStudentClick(student_id) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania numeru księgi uczniów
    $('.createForm .cancelAdd').click(function(){
        $('aside.createForm').hide();
    });

    $('.createForm .add').click(function(){
        addBookOfStudents(student_id);
        $('aside.createForm').hide();
    });
}

function addBookOfStudents(student_id) {   // zapisanie numeru księgi ucznia do bazy danych
    var school_id   = $('.createForm select[name="school_id"]').val();
    var number      = $('.createForm input[name="number"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow",
        data: { student_id: student_id, school_id: school_id, number: number },
        success: function() {
            $('td.bookOfStudent').html('<td class="bookOfStudent" style="color: #f77; background-color: #228;" data-book_of_student_id="4469">'+number+'</td>');
        },
        error: function() {
            var error = '<aside class="createForm" style="background: red; padding: 7px;">Błąd dodawania numeru księgi ucznia.</aside>';
            $('aside.createForm').replaceWith(error);
        },
    });
}

function editBookOfStudentClick() {     // kliknięcie przycisku modyfikowania numeru księgi ucznia
    $('#studentGrades').delegate('.bookOfStudent', 'click', function() {
        var id = $(this).data('book_of_student_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/ksiega_uczniow/"+id+"/edit",
            data: { id: id, version: "forStudent" },
            success: function(result) {
                $("#studentGradesTable tr.create").before(result);
                updateBookOfStudentClick();
            },
            error: function() {
                var error = '<tr><td colspan="6" class="error">Nie można utworzyć formularza dla modyfikowania numeru księgi ucznia!</td></tr>';
                $("#studentGradesTable tr.create").before(error);
            }
        });
    });
}

function updateBookOfStudentClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania numeru księgi ucznia
    $('.editRowForBookOfStudent .cancelUpdate').click(function(){
        $.when( $('tr.editRowForBookOfStudent').hide(750) ).then(function () {
            $('tr.editRowForBookOfStudent').remove();
        });
    });

    $('.editRowForBookOfStudent .update').click(function(){
        var id = $(this).data('book_of_student_id');
        $('td[data-book_of_student_id='+id+']').animate({opacity: '1%'}, 1000);
        updateBookOfStudent(id);            
        $.when( $('tr.editRowForBookOfStudent').hide(750) ).then(function () {
            $('tr.editRowForBookOfStudent').remove();
        });
    });

    $('.editRowForBookOfStudent .destroy').click(function(){
        var id = $(this).data('book_of_student_id');
        $('td[data-book_of_student_id='+id+']').animate({opacity: '1%'}, 1000);
        destroyBookOfStudent(id);            
    });
}

function updateBookOfStudent(id) {   // zapisanie numeru księgi ucznia w bazie danych
    var student_id  = $('tr[data-book_of_student_id="'+id+'"] input[name="student_id"]').val();
    var school_id   = $('tr[data-book_of_student_id="'+id+'"] select[name="school_id"]').val();
    var number      = $('tr[data-book_of_student_id="'+id+'"] input[name="number"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow/"+id,
        data: { student_id: student_id, school_id: school_id, number: number },
        success: function(resultId) {
            if(id==resultId) { $('td[data-book_of_student_id='+id+']').html(number); }
            $('td[data-book_of_student_id='+id+']').animate({opacity: '100%'}, 1000);
        },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Nie mogę zmienić numeru księgi ucznia!</td></tr>';
            $("#studentGradesTable tr.create").replaceWith(error);
            $('td[data-book_of_student_id='+id+']').animate({opacity: '100%'}, 1000);
        },
    });
}

function destroyBookOfStudent(id) {  // usunięcie numeru księgi ucznia (z bazy danych)
    $.ajax({
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow/" + id,
        success: function() {
            $("tr.editRowForBookOfStudent").remove();
            var td = '<td class="showCreateFormForBookOfStudent" style="opacity: 10%;"><button class="btn btn-secondary"><i class="fas fa-plus"></i></button><aside class="createForm"></aside></td>';
            $('td[data-book_of_student_id='+id+']').replaceWith(td);
            $('td.showCreateFormForBookOfStudent').animate({opacity: '100%'}, 1000);
            showCreateFormForBookOfStudentClick();
        },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Nie można usunąć numeru księgi ucznia. Spróbuj później.</td></tr>';
            $("tr.editRowForBookOfStudent").replaceWith(error);
        }
    });
    return false;
}


function refreshBookOfStudent(id) {  // odświeżenie tabeli z numerami księgi dla ucznia
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ksiega_uczniow/refresh",
        data: { id: id, version: "tableDataForId" },
        success: function(result) {
            $('td.bookOfStudent').replaceWith(result);
            showCreateFormForBookOfStudentClick();
        },
        error: function() { alert('błąd odświeżania komórki z numerem księgi ucznia'); },
    });
}

// ------------ usunięcie ucznia z klasy od aktualnej daty wraz z usunięciem z grup ------------ //
function removeYesterdayClick() {
    $('.removeYesterday').click(function(){
        var student_grade_id = $(this).data('student_grade_id');
        var student_id = $('input#student_id').val();
        var yesterday = $('#yesterday').val();
        var lp = $(this).parent().parent().children(":first").html();
        removeFromGroups(student_id, yesterday);
        addRemoveToStudentHistory(student_id, yesterday);
        updateEndStudentGrade(student_grade_id, yesterday, lp);
        return false;
    });
}

function removeFromGroups(student_id, end) {     // usunięcie ucznia z wszystkich grup do których należy w klasie (ustawienie daty końcowej na aktualną)
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/groupStudent/removeYesterday",
        data: { student_id: student_id, end: end },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Błąd usuwania ucznia z grup.</td></tr>';
            $('#studentGrades tr.create').before(error);
        },
    });
}

function addRemoveToStudentHistory(student_id, end) {   // zapisanie wpisu historii ucznia w bazie danych
    var event = "wybrano dokumenty";
    var confirmation_date = 1;
    var confirmation_event = 1;
    
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/historia_ucznia",
        data: { student_id: student_id, date: end, event: event, confirmation_date: confirmation_date, confirmation_event: confirmation_event },
        success: function(id) {
            $('#studentHistory tr.create').before('<tr data-student_history_id="'+id+'"><td colspan="3">ładowanie danych</td></tr>');
            refreshRowForStudentHistory(id, true);
        },
        error: function() {
            var error = '<tr><td colspan="3" class="error">Błąd w czasie dodawania historii ucznia.</td></tr>';
            $("#studentHistory tr.create").before(error);
        },
    });   
}

function updateEndStudentGrade(id, end, lp) {   // zapisanie klasy ucznia w bazie danych
    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/studentGrade/updateEnd",
        data: { id: id, end: end },
        success: function(id) {  refreshRowForStudentGrades(id, lp);  },
        error: function() {
            var error = '<tr><td colspan="6" class="error">Błąd modyfikowania końcowej daty dla przynależności ucznia do klasy.</td></tr>';
            $('tr[data-student_grade_id='+id+']').after(error).hide();
        },
    });
}
*/


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    clickShowCreateRowButtonForStudentGrade();
    // clickShowEditFormButtonForStudentGrade();
    // clickDestroyButtonForStudentGrade();
    // clickShowExchangeFormButton();
    clickError();
});