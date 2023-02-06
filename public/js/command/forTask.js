// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 ------------------------ //
// ---------------------------- wydarzenia dla widoku zadanie/info  ---------------------------- //
const SLIDE_UP=750, SLIDE_DOWN=750, FADE_IN=1275, FADE_OUT=750;
const ROUTE_NAME="polecenie", NUMBER_OF_FIELDS = 8, TABLE_NAME="#commands";


// ----------------- kliknięcie przycisku uruchamiającego formularz dodawania ------------------ //
function clickShowCreateRowButton() {
    $('#showCreateRow').click(function(){
        var CreateForm = new Form();
        CreateForm.getCreateForm();   // uruchomienie metody pobierającej formularz
    });
}

// --------------- kliknięcie przycisku uruchamiającego formularz zamiany (dodania nowego) rozszerzenia ---------------- //
// function clickShowExchangeFormButton() {
    // $('#enlargementsSection').delegate('button.exchange', 'click', function() {     // tworzenie formularza modyfikowania
        // var ExchangeForm = new Form();
        // var id = $(this).data('enlargement_id');
        // ExchangeForm.getExchangeForm(id);
    // });
// }

// --------------- kliknięcie przycisku uruchamiającego formularz modyfikowania ---------------- //
// function clickShowEditFormButton() {
    // $('#enlargementsSection').delegate('button.edit', 'click', function() {     // tworzenie formularza modyfikowania
        // var EditForm = new Form();
        // var id = $(this).data('enlargement_id');
        // EditForm.getEditForm(id);
    // });
// }

// --------------------------- kliknięcie przycisku usuwania rekordu --------------------------- //
// function clickDestroyButton() {
    // $('#enlargementsSection').delegate('button.destroy', 'click', function() {
        // var id = $(this).data("enlargement_id");
        // var enlargement = new Enlargement(id);
        // enlargement.delete();
    // });
// }


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

    // getEditForm(id) {   // pobieranie widoku formularza modyfikowania
        // var EditForm = new ShowForm();
        // $.ajax({
            // type: "GET",
            // headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            // url: "http://localhost/school/" +ROUTE_NAME+ "/"+id+"/edit",
            // data: { id: id, lp: 0, version: "forStudent" },
            // success: function(result) { EditForm.showSuccessForEdit(id, result); },
            // error: function() { EditForm.showErrorForEdit(id); },
        //  });
    // }
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
            alert('Dokończ programować obsługę przycisku w skrypcie command/forTask.js');
            $.when( $('#createRow').fadeOut(FADE_OUT) ).then(function() {
                $('#showCreateRow').slideDown(SLIDE_DOWN);
                var command = new Command();
                command.add();
            });
        });
    }

    // showErrorForExchange(id) {      // nieudane pobranie widoku formularza zamiany
        // var communique = "Nie mogę utworzyć formularza do zamiany rozszerzenia.";
        // var error = '<li class="error">' +communique+ '</li>';
        // $('li[data-enlargement_id="' +id+ '"]').before(error);
        // $('li.error').hide().slideDown(SLIDE_DOWN);
    // }

    // showSuccessForExchange(id, result) {    // udane pobranie widoku formularza zamiany: pokazanie formularza na stronie
        // $('li[data-enlargement_id="' +id+ '"]').replaceWith(result);
        // $('table.editForm').hide().show(FADE_IN);
        // $('select[name="subject_id"]').focus();
        // this.clickExchangeFormButtons();
    // }

    // clickExchangeFormButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        // $('.exchangeForm button.cancelUpdate').click(function() {   // kliknięcie przycisku "anuluj"
            // var id = $(this).data("enlargement_id");
            // var result = new ShowResultForOperation(id);
            // $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { result.exchangeSuccess() });
        // });

        // $('.exchangeForm button.update').click(function() {          // kliknięcie przycisku "zapisz zmiany"
            // var id = $(this).data("enlargement_id");
            // var enlargement = new Enlargement(id);
            // $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { enlargement.exchange(); });
        // });
    // }

    // showErrorForEdit(id) {              // nieudane pobranie widoku formularza modyfikowania
        // var communique = "Nie mogę utworzyć formularza do zmiany informacji o rozszerzeniu.";
        // var error = '<li class="error">' +communique+ '</li>';
        // $('li[data-enlargement_id="' +id+ '"]').before(error);
        // $('li.error').hide().slideDown(SLIDE_DOWN);
    // }

    // showSuccessForEdit(id, result) {    // udane pobranie widoku formularza dodawania: pokazanie formularza na stronie
        // $('li[data-enlargement_id="' +id+ '"]').replaceWith(result);
        // $('table.editForm').hide().show(FADE_IN);
        // $('select[name="subject_id"]').focus();
        // this.clickEditFormButtons();
    // }

    // clickEditFormButtons() {  // naciśnięcie przyciku w formularzu modyfikowania
        // $('.editForm button.cancelUpdate').click(function() {   // kliknięcie przycisku "anuluj"
            // var id = $(this).data("enlargement_id");
            // var result = new ShowResultForOperation(id);
            // $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { result.updateSuccess() });
        // });

        // $('.editForm button.update').click(function() {          // kliknięcie przycisku "zapisz zmiany"
            // var id = $(this).data("enlargement_id");
            // var enlargement = new Enlargement(id);
            // $.when( $('li[data-enlargement_id="' +id+ '"]').hide(FADE_OUT) ).then( function() { enlargement.update(); });
        // });
    // }
}

/*
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
        var ShowResult = new ShowResultForOperation();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME,
            data: { student_id: student_id, subject_id: subject_id, level: level, choice: choice, resignation: resignation },
            success: function(id) { ShowResult.addSuccess(id, choice); },
            error: function() { ShowResult.addError(); },
        });
    }

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
            success: function() { ShowResult.updateSuccess(choice) },
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

    addSuccess(id, choice) {    // udane dodanie: pobranie widoku z informacją o nowym rekordzie
        var Insert = new InsertNewEnlargementToHTML();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: 0, version: "forStudent" },
            success: function(result) { Insert.showSuccess(result, choice); },
            error: function() {  Insert.showError();  },
        });
    }

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

    updateError() {     // nieudane zapisane zmian wyboru rozszerzenia
        $('li[data-enlargement_id="' +this.id+ '"]').html('Nie udało się zapisać zmian. Odśwież stronę aby zobaczyć poprzedni rekord.');
        $('li[data-enlargement_id="' +this.id+ '"]').addClass('error').show(SLIDE_DOWN);
    }

    updateSuccess(choice=0) {   // udane zapisanie zmian: pobranie widoku z informacją o zmienionym rekordzie
        var id = this.id;
        var updateElement = new UpdateElementInHTML(id);
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +ROUTE_NAME+ "/refreshRow",
            data: { id: id, lp: 0, version: "forStudent" },
            success: function(result) { updateElement.showSuccess(result, choice); },
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
            var destroyDIV = $('li[data-enlargement_id="' +id+ '"]').parent().parent();
            $('li[data-enlargement_id="' +id+ '"]').remove();
            if( $(destroyDIV).children('ul').children('li').length == 0 )
                $.when( $(destroyDIV).slideUp(SLIDE_UP) ).then(function() { $(destroyDIV).remove() });
        });
    }
}

// ------------------- wstawienie informacji o nowym rekordzie do kodu HTML -------------------- //
class InsertNewEnlargementToHTML {
    showError() {           // nieudane pobranie pola z nowym rozszerzeniem
        $('#showCreateForm').before('<p class="error">Nie udało się odświeżyć sekcji z wyborami rozszerzeń. Odśwież stronę by zobaczyć wyniki.</p>');
        $('p.error').hide().slideDown(SLIDE_DOWN);
    }

    showSuccess(result, choice) {   // poprawne pobranie pola z nowym rozszerzeniem - dodanie go do strony
        var isAdd = false;
        $('#enlargementsSection div').each(function() {
            if( !isAdd && $(this).data('choice') > choice ) {
                var newDIV = '<div data-choice="' +choice+ '">';
                newDIV += '<header>od <time datetime="' +choice+ '">' +choice+ '</time></header><ul>';
                newDIV += result + '</ul></div>';
                $(this).before(newDIV);
                $('div[data-choice="' +choice+ '"]').hide().slideDown(975);
                isAdd = true;    
            }
            if( $(this).data('choice') == choice ) {
                $(this).children('ul').append(result);
                isAdd = true;
            }
        });
        if(!isAdd) {
            var newDIV = '<div data-choice="' +choice+ '">';
            newDIV += '<header>od <time datetime="' +choice+ '">' +choice+ '</time></header><ul>';
            newDIV += result + '</ul></div>';
            $('#showCreateForm').before(newDIV);
            $('div[data-choice="' +choice+ '"]').hide().slideDown(975);
        }
    }
}

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

// ---------------- odświeżenie informacji o zmienionym rekordzie w kodzie HTML ---------------- //
class UpdateElementInHTML {
    constructor(id) {
        this.id = id;
    }

    showError() {           // nieudane pobranie widoku z informacjami o zmienionym rekordzie
        $('li[data-enlargement_id="' +this.id+ '"]').html('Nie udało się odświeżyć pola z wyborami rozszerzeń. Odśwież stronę by zobaczyć wyniki.');
        $('li[data-enlargement_id="' +this.id+ '"]').addClass('error').show(SLIDE_DOWN);
    }

    showSuccess(result, choice) {   // poprawne pobranie widoku z informacjami o zmienionym rekordzie: wstawienie zmian do kodu HTML
        var oldChoice = $('li[data-enlargement_id="' +this.id+ '"]').parent().parent().children('header').children('time').html();
        if(choice==0 || choice==oldChoice) {
            $('li[data-enlargement_id="' +this.id+ '"]').replaceWith(result);
            $('li[data-enlargement_id="' +this.id+ '"]').hide().slideDown(SLIDE_DOWN);
            return;
        }
        $('li[data-enlargement_id="' +this.id+ '"]').remove();
        var isAdd = false;
        $('#enlargementsSection div').each(function() {
            if( $(this).children('ul').children('li').length == 0 ) $.when( $(this).slideUp(SLIDE_UP) ).then(function() { $(this).remove() });
            if( !isAdd && $(this).data('choice') > choice ) {
               var newDIV = '<div data-choice="' +choice+ '">';
               newDIV += '<header>od <time datetime="' +choice+ '">' +choice+ '</time></header><ul>';
               newDIV += result + '</ul></div>';
               $(this).before(newDIV);
               $('div[data-choice="' +choice+ '"]').hide().slideDown(975);
               isAdd = true;    
            }
            if( $(this).data('choice') == choice ) {
                $(this).children('ul').append(result);
                isAdd = true;
            }
        });
        if(!isAdd) {
            var newDIV = '<div data-choice="' +choice+ '">';
            newDIV += '<header>od <time datetime="' +choice+ '">' +choice+ '</time></header><ul>';
            newDIV += result + '</ul></div>';
            $('#showCreateForm').before(newDIV);
            $('div[data-choice="' +choice+ '"]').hide().slideDown(975);
        }
    }
}

function clickError() {     // kliknięcie dowolnego obiektu o klasie .error - usunięcie go ze strony
    $('#enlargementsSection').delegate('.error', 'click', function() {
        $.when( $(this).slideUp(SLIDE_UP) ).then( function() {
            $(this).remove();
        });
    });
}
*/


/*
// ----------------------------------- zarządzanie zadaniami ----------------------------------- //
function refreshRow(id, type, lp=0) {  // odświeżenie wiersza z zadaniem o podanym identyfikatorze
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/command/refreshRow",
        data: { id: id, lp: lp },
        success: function(result) {
            if(type=="add"){
                $('tr.create').before(result);
                $('tr[data-command_id='+id+']').show(1000);
                $('#showCreateRow').show();
            }
            else {
                $.when( $('tr.editRow[data-command_id='+id+']').hide(1000) ).then(function() {
                    $('tr.editRow[data-command_id='+id+']').remove();
                    $('tr[data-command_id='+id+']').replaceWith(result);
                    $('tr[data-command_id='+id+']').show(1000);
                });
            }
        },
        error: function() {
            var error = '<tr><td class="error" colspan="8">Błąd odświeżania wiersza polecenia!</td></tr>';
            $('tr.create').before(error);
        },
    });
}

function showCreateRowClick() {
    $('#showCreateRow').click(function(){
        $('#commands').animate({width: '1400px'}, 500);
        $(this).hide();
        showCreateRow();
        return false;
    });
}

function showCreateRow() {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/polecenie/create",
        success: function(result) {
            $('#commands').append(result);
            $('tr#createRow').animate({opacity: '100%'}, 2000);
        },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem dodawania polecenia.</td></tr>';
            $('#commands tr.create').after(error);
        },
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania polecenia
    $('#commands').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(1000) ).then( function() {
            $('#createRow').remove();
            $('#showCreateRow').show(1000);
        });
    });

    $('#commands').delegate('#add', 'click', function() {
        add();
        $.when( $('#createRow').hide(1000) ).then( function() {
            $('#createRow').remove();
            $('#showCreateRow').show(1000);
        });
    });
}

function add() {   // zapisanie zadania w bazie danych
    var task_id      = $('#createRow input[name="task_id"]').val();
    var number      = $('#createRow input[name="number"]').val();
    var command     = $('#createRow input[name="command"]').val();
    var description = $('#createRow input[name="description"]').val();
    var points      = $('#createRow input[name="points"]').val();
    var lp = $('input[name="lp"]').val();

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/polecenie",
        data: { task_id: task_id, number: number, command: command, description: description, points: points },
        success: function(id) {  refreshRow(id, 'add', lp);  },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Nie można dodać polecenia!</p></td></tr>';
            $('#commands tr.create').after(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania polecenia
    $('#commands').delegate('button.edit', 'click', function() {
        var id = $(this).data('command_id');
        var lp = $(this).parent().parent().children(":first").html();
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/polecenie/"+id+"/edit",
            data: { id: id },
            success: function(result) {
                $('tr[data-command_id='+id+']').before(result).hide();
                updateClick(lp);
            },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Błąd tworzenia wiersza z formularzem modyfikowania polecenia.</td></tr>';
                $('tr[data-command_id='+id+']').after(error).hide();
            },
        });
    });
}

function updateClick(lp) {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia modyfikowania polecenia
    $('.cancelUpdate').click(function() {
        var id = $(this).data('command_id');
        $.when( $('.editRow[data-command_id='+id+']').hide(1000) ).then(function() {
            $('.editRow[data-command_id='+id+']').remove();
            $('tr[data-command_id='+id+']').show(1000);
        });
    });

    $('.update').click(function(){
        var id = $(this).data('command_id');
        update(id, lp);
        $.when( $('.editRow[data-command_id='+id+']').hide(1000) ).then(function() {
            $('.editRow[data-command_id='+id+']').remove();
            $('tr[data-command_id='+id+']').show(1000);
        });
    });
}

function update(id, lp) {   // zapisanie zmian polecenia w bazie danych
    var task_id     = $('tr[data-command_id='+id+'] input[name="task_id"]').val();
    var number      = $('tr[data-command_id='+id+'] input[name="number"]').val();
    var command     = $('tr[data-command_id='+id+'] input[name="command"]').val();
    var description = $('tr[data-command_id='+id+'] input[name="description"]').val();
    var points      = $('tr[data-command_id='+id+'] input[name="points"]').val();

    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/polecenie/"+id,
        data: { id: id, task_id: task_id, number: number, command: command, description: description, points: points },
        success: function() { refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<tr><td colspan="8" class="error">Nie można zmienić danych polecenia!</td></tr>';
            $('tr[data-command_id='+id+'].editRow').after(error).hide();
        },
    });
}

function destroyClick() {  // usunięcie zadania (z bazy danych)
    $('#commands').delegate('.destroy', 'click', function() {
        var id = $(this).data('command_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/polecenie/" + id,
            success: function() {
                $.when( $('tr[data-command_id='+id+']').hide(1000) ).then(function() {
                    $('tr[data-command_id='+id+']').remove();
                });
            },
            error: function() {
                var error = '<tr><td colspan="8" class="error">Nie można usunąć polecenia.</td></tr>';
                $('tr[data-command_id='+id+']').after(error).hide();
            }
        });
        return false;
    });
}
*/

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    clickShowCreateRowButton();
    // showCreateRowClick();
    // addClick();
    // editClick();
    // destroyClick();
});