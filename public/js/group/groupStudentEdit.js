// -------------------- (C) mgr inż. Bartłomiej Trojnar; (I) listopad 2020 --------------------- //
// -------------------- wydarzenia w formularzu edytowania ucznia w grupie --------------------- //

function proposedDateStartClick() {     // kliknięcie w proponowaną datę początkową dla ucznia w grupie (wpisanie daty w pole data_start)
    $('section#main-content').delegate('#proposedDateStart li span', 'click', function() {
        $('input[name="date_start"]').val( $(this).html() );
        var date_start = $(this).html().trim();
        var date_end = $('input#date_end').val();
        datesValidate(date_start, date_end);
        return false;
    });
}

function proposedDateEndClick() {       // kliknięcie w proponowaną datę końcową dla ucznia w grupie (wpisanie daty w pole data_end)
    $('section#main-content').delegate('#proposedDateEnd li span', 'click', function() {
        $('input[name="date_end"]').val( $(this).html().trim() );
        var date_start = $('input#date_start').val();
        var date_end = $(this).html().trim();
        datesValidate(date_start, date_end);
        return false;
    });
}

function dateStartChange() {        // po zmianie daty początkowej - sprawdzenie dat grupy
    $('section#main-content').delegate('input#date_start', 'change', function() {
        date_start = $(this).val();
        date_end = $('input#date_end').val();
        datesValidate(date_start, date_end);
        return false;
    });
}
function dateEndChange() {          // po zmianie daty końcowej - sprawdzenie tej daty
    $('section#main-content').delegate('input#date_end', 'change', function() {
        var date_start = $('input#date_start').val();
        var date_end = $(this).val();
        datesValidate(date_start, date_end);
        return false;
    });
}
function datesValidate(date_start, date_end) {      // sprawdzenie czy daty są prawidłowe
    $('div#error').html('').addClass('hide');
    $('input[name="validate"').val(1);
    if( date_start == '' ) {
      $('div#error').html('data początkowa nie może być pusta').removeClass('hide');
      $('input[name="validate"').val(0);
    }
    if( date_end == '' ) {
      $('div#error').html('data końcowa nie może być pusta').removeClass('hide');
      $('input[name="validate"').val(0);
    }
    if( date_start > date_end ) {
      $('div#error').html('data początkowa nie może być późniejsza niż data końcowa').removeClass('hide');
      $('input[name="validate"').val(0);
    }
    if( date_start < $('#groupDateStart').html() ) {
      $('div#error').html('data początkowa nie może być wcześniejsza niż data początkowa grupy').removeClass('hide');
      $('input[name="validate"').val(0);
    }
    if( date_end > $('#groupDateEnd').html() ) {
      $('div#error').html('data końcowa nie może być późniejsza niż data końcowa grupy').removeClass('hide');
      $('input[name="validate"').val(0);
    }
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    dateStartChange();
    dateEndChange();

    proposedDateStartClick();
    proposedDateEndClick();
});