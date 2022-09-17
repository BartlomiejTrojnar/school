// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 17.09.2022 ------------------------ //
// -------------------- wydarzenia na stronie wyświetlania numerów uczniów --------------------- //
function helpButtonsClick() { // obsługa kliknięcia w któryś z przycisków pomocy
    $('.answer').hide();
    helpMoveNumbersClick();
    helpConfirmNumbersClick();
    helpCopyStudentNumbersClick();
    helpAddNumbersForGradeClick();
}
function helpMoveNumbersClick() {   // pokazanie pola z podpowiedzią dla przycisków "w górę, w dół"
    $('#moveNumbers .help').bind('click', function(){
        $('.answer').slideUp(1000);
        $('#moveNumbers .answer').slideDown(2000);
    });
}
function helpCopyStudentNumbersClick() { // pokazanie pola z podpowiedzią dla przycisku "kopiuj numery"
    $('#copyStudentNumbers .help').bind('click', function(){
        $('.answer').slideUp(1000);
        $('#copyStudentNumbers .answer').slideDown(2000);
    });
}
function helpConfirmNumbersClick() {    // pokazanie pola z podpowiedzią dla przycisku "potwierdź numery"
    $('#confirmNumbers .help').bind('click', function(){
        $('.answer').slideUp(1000);
        $('#confirmNumbers .answer').slideDown(2000);
    });
}
function helpAddNumbersForGradeClick() {   // pokazanie pola z podpowiedzią dla przycisku "dodaj numery dla wszystkich uczniów klasy"
    $('#addNumbersForGrade .help').bind('click', function(){
        $('.answer').slideUp(1000);
        $('#addNumbersForGrade .answer').slideDown(2000);
    });
}


function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="school_year_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/rok_szkolny/change/"+ $(this).val(),
            success: function() {   refreshSection( $('input#grade_id').val() ); }
        });
        return false;
    });
}

function refreshSection(grade_id) {  // odświeżenie tabeli z numerami uczniów
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/student_numbers/refreshSection",
        data: { grade_id: grade_id, version: "forGrade" },
        success: function(result) {
            $.when( $('#studentNumbers').replaceWith(result) ).then(function() {
                $('#studentNumbers').hide();
                $('#studentNumbers').slideDown(1500);
            });
            schoolYearChanged();
            showCreateRowClick();
            editClick();
            destroyClick();
            helpButtonsClick();
            copyStudentNumbersClick();
            trNumberClick();
            confirmNumbersClick();
            addNumbersForGradeClick();
        },
        error: function(wynik) {
            var error = '<p class="error">Błąd odświeżania tabeli z numerami ucznia.</p>';
            $('section#main-content ul.nav').after(error);
        },
    });
}

function refreshRow(id, type="add", lp=99) {  // odświeżenie wiersza tabeli z numerem ucznia (w klasie)
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/student_numbers/refreshRow",
        data: { id: id, version: "forGrade", lp: lp },
        success: function(result) {
            if(type=="add") {
                $("tr[data-student_number_id='"+id+"']").replaceWith(result);
                $("tr[data-student_number_id='"+id+"']").show(750);
                $('#showCreateRow').show();
            }
            else {
                $("tr.editRow[data-student_number_id='"+id+"']").remove();
                $("tr[data-student_number_id='"+id+"']").replaceWith(result);
                $("tr[data-student_number_id='"+id+"']").show(750);
            }
        },
        error: function() {
            var error = '<td colspan="5" class="error">Błąd odświeżania wiersza z numerem ucznia.</td>';
            $("tr[data-student_number_id='"+id+"']").html(error);
        },
    });
}


// -------------- kopiowanie numerów uczniów dla wybranej klasy i roku szkolnego --------------- //
function copyStudentNumbersClick() {
    $('#copyStudentNumbers .run').bind('click', function(){
        var grade_id = $('#grade_id').val();
        var schoolYear_id = $('select[name="school_year_id"]').val();
        if(schoolYear_id=='0') { alert('Nie wybrano roku szkolnego!'); return false; }
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/student_numbers/copy",
            data: { grade_id: grade_id, schoolYear_id: schoolYear_id },
            success: function(result) {
                if(result==1) {
                    $('#tips').prepend('<p class="info"><i class="glyphicon-saved"></i> skopiowano!</p>');
                    refreshSection(grade_id);
                }
                else $('#copyStudentNumbers .answer').html(result).slideDown(250);
                return true;
            },
            error: function() { alert('Błąd kopiowania numerów'); },
        });
        return false;
    });
}


// ------------------------ dodawanie, zmiana i usuwanie numerów uczniów ----------------------- //
function showCreateRowClick() {
    $('#studentNumbers').delegate('#showCreateRow', 'click', function() {
        $(this).hide(1250);
        $('#studentNumbers table').animate({width: '1000px', 'margin-left': '200px'}, 1250);
        $('#studentNumbers tr.create').before('<tr id="createRow"><td colspan="5">ładowanie formularza dla dodawania numeru ucznia</td></tr>');
        showCreateRow( $('input#grade_id').val() );
        return false;
    });
}

function showCreateRow(grade_id) {
    $.ajax({
        method: "GET",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/numery_ucznia/create",
        data: { grade_id: grade_id, version: "forGrade" },
        success: function(result) {
            $('tr#createRow').replaceWith(result);
            numberClick();
        },
        error: function() {
            var error = '<td colspan="5" class="error">Błąd w czasie tworzenia formularza dla dodawania numeru ucznia.</td>';
            $("tr.create").html(error);
        },
    });
}

function numberClick() {    // kliknięcie w proponowany numer lub zwiększenie/zmniejszenie (dla widoku zmiany pojedynczego numeru ucznia)
    // kliknięcie w proponowany numer - wpisanie go do pola z numerem
    $('.studentGradeProposedNumber').bind('click', function() {
        $('#number').val($(this).html());
        return false;
    });
}

function addClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania numeru ucznia
    $('#studentNumbers').delegate('#cancelAdd', 'click', function() {
        $.when( $('#createRow').hide(750) ).then(function () {
            $('#createRow').remove();
            $('#showCreateRow').slideDown(750);
        });
    });

    $('#studentNumbers').delegate('#add', 'click', function() {
        $.when( $('#createRow').hide(750) ).then(function () {
            $('#createRow').remove();
            $('#showCreateRow').slideDown(750);
        });
        add();
    });
}

function add() {   // zapisanie numeru w bazie danych
    var student_id = $('#createRow select[name="student_id"]').val();
    var grade_id = $('#createRow input[name="grade_id"]').val();
    var school_year_id = $('#createRow select[name="school_year_id"]').val();
    var number = $('#createRow input[name="number"]').val();
    var confirmationNumber = $('#createRow input[name="confirmationNumber"]').is(':checked');
    if(confirmationNumber) confirmationNumber=1;
    var lp = $('#lastLP').val()-1+2;

    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/numery_ucznia",
        data: { student_id: student_id, grade_id: grade_id, school_year_id: school_year_id, number: number, confirmationNumber: confirmationNumber },
        success: function(id) {
            $('#studentNumbers tr.create').before('<tr data-student_number_id="'+id+'"><td colspan="5">ładowanie danych</td></tr>');
            $("#lastLP").val( lp );
            refreshRow(id, "add", lp);
        },
        error: function() {
            var error = '<td colspan="5" class="error">Błąd w czasie dodawania numeru ucznia.</td>';
            $("tr.create").html(error);
        },
    });
}

function editClick() {     // kliknięcie przycisku modyfikowania numeru ucznia
    $('#studentNumbers').delegate('button.edit', 'click', function() {
        var id = $(this).data('student_number_id');
        $.ajax({
            type: "GET",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/numery_ucznia/"+id+"/edit",
            data: { id: id, version: "forGrade" },
            success: function(result) {
                $('tr[data-student_number_id='+id+']').before(result).hide();
                updateClick();
            },
            error: function() {
                var error = '<td colspan="5" class="error">Błąd tworzenia wiersza z formularzem modyfikowania numeru ucznia.</td>';
                $('tr[data-student_number_id='+id+']').html(error);
            },
        });
    });
}

function updateClick() {     // ustawienie instrukcji po kliknięciu anulowania lub potwierdzenia dodawania numeru ucznia
    $('button.cancelUpdateStudentNumber').click(function(){
        var id = $(this).attr('data-student_number_id');
        $.when( $('tr.editRow[data-student_number_id='+id+']').hide(750) ).then(function () {
            $('tr.editRow[data-student_number_id='+id+']').remove();
            $('tr[data-student_number_id='+id+']').show(750);
        });
    });

    $('button.updateStudentNumber').click(function(){
        var id = $(this).attr('data-student_number_id');
        $.when( $('tr.editRow[data-student_number_id='+id+']').hide(750) ).then(function () {
            $('tr.editRow[data-student_number_id='+id+']').remove();
            $('tr[data-student_number_id='+id+']').show(750);
        });
        update(id);
    });
}

function update(id) {   // zapisanie numeru w bazie danych
    var student_id          = $('tr[data-student_number_id='+id+'] select[name="student_id"]').val();
    var grade_id            = $('tr[data-student_number_id='+id+'] input[name="grade_id"]').val();
    var school_year_id      = $('tr[data-student_number_id='+id+'] select[name="school_year_id"]').val();
    var number              = $('tr[data-student_number_id='+id+'] input[name="number"]').val();
    var confirmationNumber  = $('tr[data-student_number_id='+id+'] input[name="confirmationNumber"]').is(':checked');
    if(confirmationNumber)  confirmationNumber=1;
    var lp                  = $('tr[data-student_number_id='+id+'] td.lp').html();
    $.ajax({
        method: "PUT",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/numery_ucznia/"+id,
        data: { student_id: student_id, grade_id: grade_id, school_year_id: school_year_id, number: number, confirmationNumber: confirmationNumber, lp: lp },
        success: function() { refreshRow(id, "edit", lp); },
        error: function() {
            var error = '<td colspan="5" class="error">Błąd modyfikowania numeru ucznia.</p></td>';
            $('tr[data-student_number_id='+id+']').html(error);
        },
    });
}

function destroyClick() {  // usunięcie numeru ucznia (z bazy danych)
    $('#studentNumbers').delegate('button.destroy', 'click', function() {
        var id = $(this).attr('data-student_number_id');
        $.ajax({
            type: "DELETE",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/numery_ucznia/" + id,
            success: function() {
                $.when( $('tr[data-student_number_id='+id+']').hide(750) ).then(function() {
                    $('tr[data-student_number_id='+id+']').remove();
                    $("#lastLP").val( $('#lastLP').val()-1 );
                });
            },
            error: function() {
                var error = '<td colspan="5" class="error">Błąd usuwania numeru ucznia.</td>';
                $('tr[data-student_number_id='+id+']').html(error);
            }
        });
        return false;
    });
}


// -------------------------- potwierdzenie widocznych numerów uczniów ------------------------- //
function confirmNumbersClick() {    // wybór właściwych numerów
    $('#confirmNumbers .run').click(function() {
        var grade_id = $('#grade_id').val();
        var schoolYear_id = $('select[name="school_year_id"]').val();
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/student_numbers/confirmNumbers/"+grade_id+"/"+schoolYear_id,
            success: function(result) {
                if(result) $( '#studentNumbers' ).before('<p class="info">Numery potwierdzone!</p>');
            },
            error: function(result) { alert('Błąd: '+result); },
        });
    
        $( "tr.confirmation0 td.not_confirmation" ).animate({backgroundColor: "#bb7"}, 500).animate({backgroundColor: "transparent"}, 1000);
        $( "tr.confirmation0 td.not_confirmation" ).removeClass('not_confirmation');
        $( "tr.confirmation0" ).removeClass('confirmation0').addClass('confirmation1');
        
        return false;
    });
}


function trNumberClick() {  // kliknięcie w wiersz z numerem ucznia w tabeli z numerami uczniów (widok numerów dla klasy)
    $('#studentNumbers').delegate('tr.number_row td:not(.edit)', 'click', function() {
        var student_number_id = $(this).parent().attr('data-student_number_id');  // pobranie id z klikniętęgo wiersza

        // jeżeli nie był wybrany kliknięty wiersz z numerem - aktywacja go
        if( $('#student_number_id').val() != student_number_id ) {
            $('#student_number_id').val(student_number_id);
            $('#studentNumbers tr').removeClass('active');
            $(this).parent().addClass('active');
            $('#button_up').prop('disabled', false);
            $('#button_down').prop('disabled', false);
        }
        else {  // deaktywacja klikniętego wiersza jeżeli był aktywny
            $('#student_number_id').val(0);
            $(this).parent().removeClass('active');
            $('#button_up').prop('disabled', true);
            $('#button_down').prop('disabled', true);
        }
        return false;
    });
}

function buttonUpClick() {  // kliknięcie w przycisk 'w górę' - zmniejszenie zaznaczonego numeru (dla widoku numerów w klasie)
    $('#button_up').click(function() {
        var id = $('#student_number_id').val();
        var number = $('tr.active span.number').html()-1;

        // zmniejszenie numeru dla którego kliknięto przycisk
        updateRecordNumber(id, number);

        // wyszukanie i zwiększenie numeru o jeden mniejszego (dla wybranego roku szkolnego)
        searchAndIncreaseNumber(number, id);
        return false;
    });
}

function searchAndIncreaseNumber(number, actualID) {  // wyszukanie i zwiększenie numeru
    // pobranie roku szkolnego - by wyszukać numer z tego samego roku
    var schoolYearId = $('tr.active').data('school_year_id');

    // wyszukiwanie wiersza z numerem w wybranym roku szkolnym
    $('#studentNumbers span.number').each(function() {
        if( parseInt($(this).html()) == number && $(this).data('school_year_id') == schoolYearId) {
            // wprowadzenie nowych danych
            var id = $(this).data('id');
            updateRecordNumber(id, number+1);
            $('tr[data-student_number_id=' + id + ']').hide( 250 );
            $('tr[data-student_number_id=' + actualID + ']').fadeOut(250, function() {
                $('tr[data-student_number_id=' + id + '] span.number').html( number+1 );
                $('tr[data-student_number_id=' + actualID + '] span.number').html( number );
                $('tr[data-student_number_id=' + id + ']').insertAfter( $('tr.active') );  // przestawienie kolejności wierszy z numerami uczniów
                $('tr[data-student_number_id=' + actualID + ']').show( 250 );
                $('tr[data-student_number_id=' + id + ']').show( 1750 );
            });
        }
    });
}

function buttonDownClick() {    // kliknięcie w przycisk 'w dół' - zwiększenie zaznaczonego numeru (dla widoku numerów w klasie)
    $('#button_down').bind('click', function() {
        $(this).prop('disabled', true);
        var id = $('#student_number_id').val();
        var number = $('tr.active span.number').html()-1+2;

        // zwiększenie numeru dla którego kliknięto przycisk
        $.when( updateRecordNumber(id, number) ).then( $(this).prop('disabled', false) );

        // wyszukanie i zmniejszenie numeru o jeden większego (dla wybranego roku szkolnego)
        searchAndDecreaseNumber(number, id);
        return false;
    });
}

function searchAndDecreaseNumber(number, actualID) {  // wyszukanie i zmniejszenie numeru
    // pobranie roku szkolnego - by wyszukać numer z tego samego roku
    var schoolYearId = $('tr.active').data('school_year_id');

    // wyszukiwanie wiersza z numerem w wybranym roku szkolnym
    $('#studentNumbers span.number').each(function() {
        if( parseInt($(this).html()) == number && $(this).data('school_year_id') == schoolYearId) {
            // wprowadzenie nowych danych
            var id = $(this).data('id');
            updateRecordNumber(id, number-1);
            $('tr[data-student_number_id=' + id + ']').hide( 250 );
            $('tr[data-student_number_id=' + actualID + ']').fadeOut(250, function() {
                $('tr[data-student_number_id=' + id + '] span.number').html( number-1 );
                $('tr[data-student_number_id=' + actualID + '] span.number').html( number );
                $('tr[data-student_number_id=' + id + ']').insertBefore( $('tr.active') );  // przestawienie kolejności wierszy z numerami uczniów
                $('tr[data-student_number_id=' + actualID + ']').show( 250 );
                $('tr[data-student_number_id=' + id + ']').show( 1750 );
            });
        }
    });
}

function updateRecordNumber(id, number) {   // zmiana numeru w bazie danych
    $.ajax({
        method: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/student_numbers/updateNumber",
        data: { id: id, number: number },
        error: function(result) { alert(result) },
    });
    return false;
}


// --------- nadawanie numerów dla wszystkich uczniów bieżącej klasy i roku szkolnego ---------- //
function addNumbersForGradeClick() {
    $('#addNumbersForGrade .run').bind('click', function(){
        var grade_id = $('#grade_id').val();
        var schoolYear_id = $('select[name="school_year_id"]').val();
        if(schoolYear_id=='0') { alert('Nie wybrano roku szkolnego!'); return false; }
        $.ajax({
            method: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/student_numbers/addNumbersForGrade",
            data: { grade_id: grade_id, schoolYear_id: schoolYear_id },
            success: function(result) {
                if(result==1) {
                    $('#studentNumbers').before('<p class="info"><i class="glyphicon-saved"></i> Numery utworzone!</p>');
                    refreshSection(grade_id);
                }
                else $('#copyStudentNumbers .answer').html(result).slideDown(250);
                return true;
            },
            error: function() { alert('Błąd tworzenia numerów'); },
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    schoolYearChanged();
    showCreateRowClick();
    addClick();
    editClick();
    destroyClick();

    helpButtonsClick();
    confirmNumbersClick();
    trNumberClick();
    buttonUpClick();
    buttonDownClick();
    copyStudentNumbersClick();
    addNumbersForGradeClick();
});