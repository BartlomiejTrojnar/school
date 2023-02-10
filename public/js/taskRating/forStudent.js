// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 10.02.2023 ------------------------ //
// --------------------------- wydarzenia dla widoku uczen/zadania  ---------------------------- //
const SLIDE_UP=750, SLIDE_DOWN=750, FADE_IN=1275, FADE_OUT=750;
const ROUTE_NAME="ocena_zadania", NUMBER_OF_FIELDS = 13, TABLE_NAME="#taskRatings", DATA_NAME="task_rating_id";


// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateRowButton() {
    $('#showCreateRow').click(function(){
        var CreateFormRow = new FormRow();
        CreateFormRow.getCreateFormRow();   // uruchomienie metody pobierającej formularz
    });
}

// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
function clickShowEditRowButton() {
    $('#taskRatings').delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        var EditForm = new Form();
        var id = $(this).data(DATA_NAME);
        var lp = parseInt($('tr[data-' +DATA_NAME+ '="'+id+'"]').children(":first").children(":first").html());
        EditForm.getEditFormRow(id, lp);
    });
}

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
function clickDestroyButton() {
    $('#taskRatings').delegate('button.destroy', 'click', function() {
        var id = $(this).data(DATA_NAME);
        var taskRating = new TaskRating(id);
        taskRating.delete();
    });
}

// ------------------------------- pobieranie widoku formularzy -------------------------------- //
class Form {
    getCreateFormRow() {   // pobieranie widoku formularza dodawania
        $('#showCreateRow').slideUp(SLIDE_UP);
        var CreateRow = new ShowRow();
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { version: "forStudent", student_id: $('#student_id').val(), },
            url: "http://localhost/school/" +ROUTE_NAME+ "/create",
            success: function(result) { CreateRow.showSuccess(result); },
            error: function() {  CreateRow.showError(); },
        });
    }

    getEditFormRow(id, lp) {   // pobieranie widoku formularza modyfikowania
        var EditFormRow = new ShowRow();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            data: { id: id, lp: lp, version: "forStudent" },
            success: function(result) { EditFormRow.showSuccessForEdit(id, result); },
            error: function() { EditFormRow.showErrorForEdit(id); },
         });
    }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowRow {
    showError() {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania informacji o ocenie zadania dla ucznia.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $(TABLE_NAME+ ' tr.create').after(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccess(result) {          // udane tworzenie formularza dodawania: wyświetlenie formularza w tabeli
        $(TABLE_NAME+ ' tr.create').before(result);
        $('#createRow').hide().fadeIn(FADE_IN);
        $('select[name="task_id"]').focus();
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
                var taskRating = new TaskRating();
                taskRating.add();
            });
        });
    }

    showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        var communique = "Nie mogę utworzyć formularza do zmiany oceny zadania.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '="'+id+'"]').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccessForEdit(id, result) {    // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        $.when( $('tr[data-' +DATA_NAME+ '="'+id+'"]').fadeOut(FADE_OUT) ).then(function() {
            $('tr[data-' +DATA_NAME+ '="'+id+'"]').replaceWith(result);
            $('tr.editRow[data-' +DATA_NAME+ '="'+id+'"]').hide().fadeIn(FADE_IN);
            $('select[name="task_id"]').focus();
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
            var taskRating = new TaskRating(id);
            $.when( $('li[data-' +DATA_NAME+ '="' +id+ '"]').hide(FADE_OUT) ).then( function() { taskRating.update(); });
        });
    }
}

// ---------------------- operacje na rekordach dotyczących rozszerzenia ----------------------- //
class TaskRating {
    constructor(id=0) {
        this.id = id;
    }

    add() {     // wstawienie rekordu do bazy danych
        var student_id  = $('#createRow input[name="student_id"]').val();
        var task_id     = $('#createRow select[name="task_id"]').val();
        var deadline    = $('#createRow input[name="deadline"]').val();
        var implementation_date = $('#createRow input[name="implementation_date"]').val();
        var version     = $('#createRow input[name="version"]').val();
        var importance  = $('#createRow input[name="importance"]').val();
        var rating_date = $('#createRow input[name="rating_date"]').val();
        var points      = $('#createRow input[name="points"]').val();
        var rating      = $('#createRow input[name="rating"]').val();
        var comments    = $('#createRow input[name="comments"]').val();
        var diary       = $('#createRow input[name="diary"]').is(':checked');
        if(diary)   diary = 1;  else diary = 0;
        var entry_date  = $('#createRow input[name="entry_date"]').val();
        $('tr#createRow').remove();
        var lp = parseInt($('#countTaskRatings').val());
        var ShowResult = new ShowResultForOperation();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date, version: version,
                importance: importance, rating_date: rating_date, points: points, rating: rating, comments: comments, diary: diary, entry_date: entry_date },
            success: function(id) { ShowResult.addSuccess(id, lp); },
            error: function() { ShowResult.addError(); },
        });
    }

    update() {  // zapisywanie zmian rekordu
        var id = this.id;
        var student_id  = $('tr[data-task_rating_id='+id+'] input[name="student_id"]').val();
        var task_id     = $('tr[data-task_rating_id='+id+'] select[name="task_id"]').val();
        var deadline    = $('tr[data-task_rating_id='+id+'] input[name="deadline"]').val();
        var implementation_date = $('tr[data-task_rating_id='+id+'] input[name="implementation_date"]').val();
        var version     = $('tr[data-task_rating_id='+id+'] input[name="version"]').val();
        var importance  = $('tr[data-task_rating_id='+id+'] input[name="importance"]').val();
        var rating_date = $('tr[data-task_rating_id='+id+'] input[name="rating_date"]').val();
        var points      = $('tr[data-task_rating_id='+id+'] input[name="points"]').val();
        var rating      = $('tr[data-task_rating_id='+id+'] input[name="rating"]').val();
        var comments    = $('tr[data-task_rating_id='+id+'] input[name="comments"]').val();
        var diary       = $('tr[data-task_rating_id='+id+'] input[name="diary"]').is(':checked');
        if(diary)   diary = 1;  else diary = 0;
        var entry_date  = $('tr[data-task_rating_id='+id+'] input[name="entry_date"]').val();
        var lp          = $('tr[data-task_rating_id='+id+'] input[name="lp"]').val();
        var ShowResult = new ShowResultForOperation(id);

        $.ajax({
            method: "PUT",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/"+id,
            data: { id: id, student_id: student_id, task_id: task_id, deadline: deadline, implementation_date: implementation_date,
                version: version, importance: importance, rating_date: rating_date, points: points, rating: rating, comments: comments,
                diary: diary, entry_date: entry_date },
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

    addError() {        // nieudane dodanie nowej oceny zadania dla ucznia
        var communique = "Nie udało się dodać oceny zadania dla ucznia.";
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
            data: { id: id, lp: lp+1, version: "forStudent" },
            success: function(result) { Insert.showSuccess(result); },
            error: function() {  Insert.showError();  },
        });
    }

    updateError() {     // nieudane zapisane zmian wyboru rozszerzenia
        var communique = "Błąd w trakcie zapisywania zmian.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '=' +this.id+ ']').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
        $.when( $('tr.editRow[data-' +DATA_NAME+ '=' +this.id+ ']').fadeOut(FADE_OUT) ).then(function() {
            $('tr.editRow[data-' +DATA_NAME+ '=' +this.id+ ']').remove();
        });
    }

    updateSuccess(lp) {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var updateElement = new UpdateElementInHTML(this.id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: this.id, lp: lp, version: "forStudent" },
            success: function(result) { updateElement.showSuccess(result); },
            error: function() {  updateElement.showError();  },
        });
    }

    destroyError() {    // nieudane usunięcie wyboru rozszerzenia
        var communique = "Błąd w trakcie usuwania oceny zadania.";
        var error = '<tr data-' +DATA_NAME+ '="' +this.id+ '"><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +DATA_NAME+ '=' +this.id+ ']').replaceWith(error);
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
        var communique = "Nie udało się odświeżyć wiersza oceną zadania. Odśwież stronę by zobaczyć wyniki.";
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
        var communique = "Nie udało się odświeżyć wiersza z oceną zadania. Odśwież stronę by zobaczyć wyniki.";
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
    $(TABLE_NAME).delegate('.error', 'click', function() {
        $.when( $(this).slideUp(SLIDE_UP) ).then( function() {
            $(this).remove();
        });
    });
}

// ----------------------------- zarządzanie ocenami zadań ucznia ------------------------------ //
///////////////////////////////////////////////////////////////////////////////////////////////////
function buttonDiaryClick() {   // kliknięcie przycisku z informacją o wpisie w dzienniku - wpisanie lub usunięcie daty wpisu do dziennika
    $('#taskRatings').delegate('.no-diary', 'click', function() {
        writeInTheDiary( $(this).data('task_rating_id') );
    });

    $('#taskRatings').delegate('.entry-diary', 'click', function() {
        removeFromDiary( $(this).data('task_rating_id') );
    });
}

function writeInTheDiary(task_rating_id) {  // wpisanie informacji że ocena jest wpisana do dziennika (wraz z datą wpisu)
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania/writeInTheDiary/"+ task_rating_id,
        success: function(result) {
            $('tr[data-task_rating_id="' +task_rating_id+ '"] td.entry_date').html(result.substr(0, 10));
            var button = '<button class="btn-warning entry-diary" data-task_rating_id="'+task_rating_id+'"><i class="fa fa-check-circle"></i></button>';
            $('tr[data-task_rating_id='+task_rating_id+'] td.diary').html(button);
        },
        error: function(result) { alert('Błąd w funkcji writeInTheDiary: '+result); },
    });
    return false;
}

function removeFromDiary(task_rating_id) {  // wpisanie informacji, że ocena nie jest wpisana do dziennika (oraz wyczyszczenie daty wpisu)
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/ocena_zadania/removeFromDiary/"+ task_rating_id,
        success: function() {
            $('tr[data-task_rating_id='+task_rating_id+'] td.entry_date').html('');
            var button = '<button class="btn-warning no-diary" data-task_rating_id="'+task_rating_id+'"><i class="fa fa-circle-o"></i></button>';
            $('tr[data-task_rating_id='+task_rating_id+'] td.diary').html(button);
        },
        error: function(result) { alert('Błąd w funkcji removeFromDiary: '+result); },
    });
    return false;
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    //alert('Dokończyć skrypt taskRating/forStudent.js - dodawanie/usuwanie oceny do/z dziennika');
    clickShowCreateRowButton();
    clickShowEditRowButton(); 
    clickDestroyButton();
    clickError();
    buttonDiaryClick();
});