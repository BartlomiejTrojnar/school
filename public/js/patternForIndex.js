// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 28.01.2023 ------------------------ //
// ------ skrypt zawierający wzory najczęściej wykonywanych operacji w widokach "/index" ------- //
const FADE_OUT=575, FADE_IN=1275;

// --------------------- odświeżanie i dowawanie wierszy w tabeli rekordów --------------------- //
export class RefreshRowService {
    constructor(numberOfFields, tableName, dataName) {
        this.numberOfFields = numberOfFields;
        this.tableName = tableName;
        this.dataName = dataName;
    }

    error(id, operation, communique) {  // błąd w trakcie odświeżania
        var error = '<tr data-' +this.dataName+ '="' +id+ '"><td class="error" colspan="' +this.numberOfFields+ '">' +communique+ '</td></tr>';
        if(operation=="add") $('tr.create').before(error);
        else $('tr[data-' +this.dataName+ '="'+id+'"]').replaceWith(error);
        $('td.error').hide(5).fadeIn(FADE_IN);
    }

    addError(communique) {              // niepowodzenie wstawienia rekordu do bazy danych
        $('tr#createRow').remove();
        var error = '<tr><td colspan="' +this.numberOfFields+ '" class="error">' +communique+ '</td></tr>';
        $(this.tableName+ ' tr.create').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    updateError(id, communique) {       // niepowodzenie w czasie zapisywania zmian w bazie danych
            var error = '<tr><td colspan="' +this.numberOfFields+ '" class="error">' +communique+ '</td></tr>';
            $('tr[data-' +this.dataName+ '='+id+']').before(error);
            $('td.error').hide().fadeIn(FADE_IN);
    }

    destroyError(id, communique) {      // niepowodzenie usuwania rekordu
        var error = '<tr data-' +this.dataName+ '="'+id+'"><td colspan="' +this.numberOfFields+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +this.dataName+ '='+id+']').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    success(id, result, operation) {    // udane przygotowanie wiersza z odświeżonym rekordem
        if(operation=="add")    { this.addSuccess(result); }
        else                    { this.updateSuccess(id, result); }
    }

    addSuccess(result) {                // odświeżanie rekordu w przypadku udanego dodawania - dodanie wiersza do tabeli
        $('tr#createRow').remove();
        $('tr.create').before(result);
        $('tr[data-' +this.dataName+ '="' +id+ '"]').fadeIn(FADE_IN);
        $('#showCreateRow').show();
    }

    updateSuccess(id, result) {         // odświeżanie rekordu w przypadku udanej modyfikacji - odświeżenie wiersza w tabeli
        var dataName = this.dataName;
        $.when( $('tr[data-' +dataName+ '="' +id+ '"]').fadeOut(FADE_OUT) ).then(function() {
            $('tr[data-' +dataName+ '="' +id+ '"]').replaceWith(result);
            $('tr[data-' +dataName+ '="' +id+ '"]').hide().fadeIn(FADE_IN);
        });
    }

    destroySuccess(id) {                // udane usunięcie rekordu - usunięcie wiersza z tabeli
        var dataName = this.dataName;
        $.when( $('tr[data-' +dataName+ '='+id+']').fadeOut(FADE_OUT) )
            .then( function() {  $('tr[data-' +dataName+ '='+id+']').remove();  });
    }
}

// ----------------------------------- zarządzanie rekordami ----------------------------------- //
// ------------------------------ tworzenie formularza dodawania ------------------------------- //
export class CreateRowService {
    constructor(numberOfFields, tableName, communique, inputName) {
        this.numberOfFields = numberOfFields;
        this.tableName = tableName;
        this.communique = communique;
        this.inputName = inputName;
    }

    show(routeTableName) {      // twrorzenie formaularza dodawania - pobranie widoku
        var numberOfFields = this.numberOfFields;
        var communique = this.communique;
        var tableName = this.tableName;
        var inputName = this.inputName;
        $.ajax({
            method: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +routeTableName+ "/create",
            success: function(result) { CreateRowService.showSuccess(result, tableName, inputName); },
            error: function() {  CreateRowService.showError(numberOfFields, communique, tableName); },
        });
    }

    static showError(numberOfFields, communique, tableName) {   // nieudane tworzenie widoku formularza dodawania
        var error = '<tr><td colspan="' +numberOfFields+ '" class="error">' +communique+ '</td></tr>';
        $(tableName+ ' tr.create').after(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    static showSuccess(result, tableName, inputName) {          // udane tworzenie formularza dodawania: wyświetlenie formularza w tabeli
        $(tableName+ ' tr.create').before(result);
        $('#createRow').hide().fadeIn(FADE_IN);
        $('input[name="' +inputName+ '"]').focus();
    }
}

// ---------------------------- tworzenie formularza modyfikowania ----------------------------- //
export class EditRowService {
    constructor(numberOfFields, dataName, communique, inputName) {
        this.numberOfFields = numberOfFields;
        this.dataName = dataName;
        this.communique = communique;
        this.inputName = inputName;
    }

    show(routeTableName, id, lp) {      // twrorzenie formaularza modyfikowania - pobranie widoku
        var numberOfFields = this.numberOfFields;
        var communique = this.communique;
        var dataName = this.dataName;
        var inputName = this.inputName;
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/" +routeTableName+ "/"+id+"/edit",
            data: { id: id, lp: lp },
            success: function(result) { EditRowService.showSuccess(id, result, dataName, inputName); },
            error: function() { EditRowService.showError(id, numberOfFields, communique, dataName ); },
         });
    }

    static showError(id, numberOfFields, communique, dataName) { // nieudane tworzenie widoku formularza modyfikowania
        var error = '<tr><td colspan="' +numberOfFields+ '" class="error">' +communique+ '</td></tr>';
        $('tr[data-' +dataName+ '="'+id+'"]').before(error);
        $('td.error').hide().fadeIn(FADE_IN);
    }

    static showSuccess(id, result, dataName, inputName) {       // udane tworzenie formularza modyfikowania: wyświetlenie formularza w tabeli
        $.when( $('tr[data-' +dataName+ '="'+id+'"]').fadeOut(FADE_OUT) ).then(function() {
            $('tr[data-' +dataName+ '="'+id+'"]').replaceWith(result);
            $('tr.editRow[data-' +dataName+ '="'+id+'"]').hide().fadeIn(FADE_IN);
            $('input[name="' +inputName+ '"]').focus();
        });
    }
}