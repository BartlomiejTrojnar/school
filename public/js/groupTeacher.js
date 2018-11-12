// --------------------------- OPERACJE DOTYCZĄCE NAUCZYCIELI W GRUPIE -------------------------- //
// --------------------- (C) mgr inż. Bartłomiej Trojnar; (II) listopad 2018 -------------------- //

// ---------------------- DODAWANIE LUB MODYFIKOWANIE NAUCZYCIELA W GRUPIE ---------------------- //
// -------------------- kliknięcie proponowanej daty początkowej lub końcowej ------------------- //
function teacherDateClick()
{
    $('.teacherDateStart').bind('click', function(){
        date_start = $(this).html();
        $('input#date_start').val(date_start);
        date_end = $('input#date_end').val();
        datesValidate(date_start, date_end);
        return false;
    });
    $('.teacherDateEnd').bind('click', function(){
        date_end = $(this).html();
        $('input#date_end').val(date_end);
        date_start = $('input#date_start').val();
        datesValidate(date_start, date_end);
        return false;
    });
}

function dateStartChange()
{
    $('input#date_start').change('click', function(){
        date_start = $(this).val();
        date_end = $('input#date_end').val();
        datesValidate(date_start, date_end);
        return false;
    });
}

function dateEndChange()
{
    $('input#date_end').change('click', function(){
        date_start = $('input#date_start').val();
        date_end = $(this).val();
        datesValidate(date_start, date_end);
        return false;
    });
}
function datesValidate(date_start, date_end) {
    $('div#error').html('').addClass('hide');
    if( date_start < $('#groupDateStart').html() )
      $('div#error').html('data początkowa nie może być wcześniejsza niż data początkowa grupy').removeClass('hide');
    if( date_end > $('#groupDateEnd').html() )
      $('div#error').html('data końcowa nie może być późniejsza niż data końcowa grupy').removeClass('hide');
    if( date_start > date_end )
      $('div#error').html('data początkowa nie może być późniejsza niż data końcowa').removeClass('hide');
    if( date_start == '' )
      $('div#error').html('data początkowa nie może być pusta').removeClass('hide');
    if( date_end == '' )
      $('div#error').html('data końcowa nie może być pusta').removeClass('hide');
}

// ----------------------------------- załadowanie dokumentu ------------------------------------ //
$(document).ready(function()
{
    teacherDateClick();
    dateStartChange();
    dateEndChange();

    date_start = $('input#date_start').val();
    date_end = $('input#date_end').val();
    datesValidate(date_start, date_end);
});