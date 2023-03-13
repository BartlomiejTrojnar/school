// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 20.02.2023 ------------------------ //
// --------------------------- wydarzenia dla widoku uczen/zadania  ---------------------------- //
const SLIDE_UP=750, SLIDE_DOWN=750, FADE_IN=1275, FADE_OUT=750;
const ROUTE_NAME="ocena_zadania", NUMBER_OF_FIELDS = 14, TABLE_NAME="#taskRatings", DATA_NAME="task_rating_id";


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
        var EditFormRow = new FormRow();
        var id = $(this).data(DATA_NAME);
        var lp = $('tr[data-' +DATA_NAME+ '="'+id+'"]').children(":first").html();
        EditFormRow.getEditFormRow(id, lp);
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
class FormRow {
    getCreateFormRow() {   // pobieranie widoku formularza dodawania
        $('#showCreateRow').slideUp(SLIDE_UP);
        var CreateRow = new ShowRow();
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { version: "forGrade", student_id: $('#student_id').val(), },
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
            data: { id: id, lp: lp, version: "forGrade" },
            success: function(result) { EditFormRow.showSuccessForEdit(id, result); },
            error: function() { EditFormRow.showErrorForEdit(id); },
         });
    }
}

// -------------------- wyświetlanie widoku formularza lub błędu na stronie -------------------- //
class ShowRow {
    showError() {           // nieudane pobranie widoku formularza dodawania
        var communique = "Błąd tworzenia wiersza z formularzem dodawania informacji o ocenie zadania w klasie.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $(TABLE_NAME+ ' tr:last').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccess(result) {          // udane tworzenie formularza dodawania: wyświetlenie formularza w tabeli
        $(TABLE_NAME+ ' tr:last').before(result);
        $('#createRow').hide().fadeIn(FADE_IN);
        $('select[name="student_id"]').focus();
        this.clickCreateRowButtons();
        this.formRowActions();
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
            ShowRow.formEditRowActions();
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

    formRowActions() {  // akcje w czasie uzupełniania formularza
        $('input[name="rating_date"]').attr("disabled", "disabled");
        $('input[name="points"]').attr("disabled", "disabled");
        $('input[name="rating"]').attr("disabled", "disabled");
        $('input[name="diary"]').attr("disabled", "disabled");
        $('input[name="entry_date"]').attr("disabled", "disabled");
        ShowRow.implementationDateChange();
        ShowRow.ratingDateChange();
        ShowRow.pointsChange();
        ShowRow.ratingChange();
        ShowRow.diaryChange();        
        ShowRow.entryDateChange();
    }

    static formEditRowActions() {  // akcje w czasie uzupełniania formularza
        this.ratingAndPointsBlocking();
        this.ratingBlocking();
        this.diaryBlocking();
        this.implementationDateChange();
        this.ratingDateChange();
        this.pointsChange();
        this.ratingChange();
        this.diaryChange();
        this.entryDateChange();
    }

    static implementationDateChange() {  $('input[name="implementation_date"]').change(function() { ShowRow.ratingAndPointsBlocking(); });  }

    static ratingDateChange() {  $('input[name="rating_date"]').change(function() { ShowRow.ratingBlocking(); });  }

    static pointsChange() {
        $('input[name="points"]').change(function() {
            if( $('input[name="points"]').val()!="" && $('input[name="rating_date"]').val()=="" ) {
                const d = new Date();
                var year = parseInt( d.getFullYear() );
                var month = parseInt( d.getMonth() ) + 1;
                if(month<10) month = '0'+month;
                var day = parseInt(d.getDate());
                if(day<10) day = '0'+day;
                var ratingDate = year + '-' + month + '-' + day;
                $('input[name="rating_date"]').val( ratingDate );
                ShowRow.ratingBlocking();
            }
        });
    }

    static ratingChange() {   $('input[name="rating"]').change(function() {  ShowRow.diaryBlocking();  });   }

    static diaryChange() {
        $('input[name="diary"]').change(function() {
            if($('input[name="diary"]').is(':checked') && $('input[name="entry_date"]').val()=="" ) {
                const d = new Date();
                var year = parseInt( d.getFullYear() );
                var month = parseInt( d.getMonth() ) + 1;
                if(month<10) month = '0'+month;
                var day = parseInt(d.getDate());
                if(day<10) day = '0'+day;
                var entryDate = year + '-' + month + '-' + day;
                $('input[name="entry_date"]').val( entryDate );
            }
        });
    }

    static entryDateChange() {
        $('input[name="entry_date"]').change(function() {
            if($('input[name="entry_date"]').val()=="" )    $('input[name="diary"]').removeAttr("checked");
            else {
                var inp = '<input type="checkbox" name="diary" checked="checked">';
                $('input[name="diary"]').replaceWith(inp);
            }
        });
    }

    static ratingAndPointsBlocking() {
        if( $('input[name="implementation_date"]').val()!="") {
            $('input[name="rating_date"]').removeAttr("disabled");
            $('input[name="points"]').removeAttr("disabled");
        }
        else    {
            $('input[name="rating_date"]').val('').attr("disabled", "disabled");
            $('input[name="points"]').val('').attr("disabled", "disabled");
            ShowRow.ratingBlocking();
        }
    }

    static ratingBlocking() {
        if($('input[name="rating_date"]').val()!="")    $('input[name="rating"]').removeAttr("disabled");
        else    {
            $('input[name="rating"]').val('').attr("disabled", "disabled");
            this.diaryBlocking();
        }
    }

    static diaryBlocking() {
        if($('input[name="rating"]').val()!="") {
            $('input[name="diary"]').removeAttr("disabled");
            $('input[name="entry_date"]').removeAttr("disabled");
        }
        else    {
            $('input[name="diary"]').attr("checked", false).attr("disabled", "disabled");
            $('input[name="entry_date"]').val('').attr("disabled", "disabled");
        }
    }
}

// ---------------------- operacje na rekordach dotyczących rozszerzenia ----------------------- //
class TaskRating {
    constructor(id=0) {
        this.id = id;
    }

    add() {     // wstawienie rekordu do bazy danych
        var student_id  = $('#createRow select[name="student_id"]').val();
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
        var student_id  = $('tr[data-task_rating_id='+id+'] select[name="student_id"]').val();
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
        var communique = "Nie udało się dodać oceny zadania.";
        var error = '<tr><td colspan="' +NUMBER_OF_FIELDS+ '" class="error">' +communique+ '</td></tr>';
        $(TABLE_NAME+ ' tr:last').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    addSuccess(id, lp) {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        var Insert = new InsertNewCommandToHTML();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: lp+1, version: "forGrade" },
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
            data: { id: this.id, lp: lp, version: "forGrade" },
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
        $(TABLE_NAME+ ' tr:last').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    showSuccess(result) {   // poprawne pobranie pola z nowym rozszerzeniem - dodanie go do strony
        $(TABLE_NAME+ ' tr:last').before(result);
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
    clickShowCreateRowButton();
    clickShowEditRowButton(); 
    clickDestroyButton();
    clickError();
    buttonDiaryClick();
});