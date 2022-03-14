// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 ------------------------ //
// -------------------------- wydarzenia na stronie wyświetlania grup -------------------------- //

// ------------------------------- DODAWANIE/MODYFIKOWANIE GRUPY -------------------------------- //
// ---------- ustawienie daty początku i końca grupy (po kliknięciu w propozycje daty ----------- //
function dateClick() {
    $('.proposedGradeDateStart').bind('click', function(){
        $('#dateStart').val($(this).html());
        var date_start = $(this).html().trim();
        var date_end = $('input[name="date_end"]').val();
        datesValidate(date_start, date_end);
        return false;
    });
    $('.proposedGradeDateEnd').bind('click', function(){
        $('#dateEnd').val($(this).html());
        var date_start = $('input[name="date_start"]').val();
        var date_end = $(this).html().trim();
        datesValidate(date_start, date_end);
        return false;
    });
}

// sprawdzenie czy daty są prawidłowe
function datesValidate(date_start, date_end) {
    $('div#error').html('').addClass('hide');
    if( date_start == '' )  $('div#error').html('data początkowa nie może być pusta').removeClass('hide');
    if( date_end == '' )    $('div#error').html('data końcowa nie może być pusta').removeClass('hide');
    if( date_start > date_end ) $('div#error').html('data początkowa nie może być późniejsza niż data końcowa').removeClass('hide');
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    dateClick();
});