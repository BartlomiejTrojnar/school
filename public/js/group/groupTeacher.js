// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 15.03.2022 ------------------------ //
// ------------------ wydarzenia na stronach operowania na nauczycielach grup ------------------ //


// ---------------------- DODAWANIE LUB MODYFIKOWANIE NAUCZYCIELA W GRUPIE ---------------------- //
// -------------------- kliknięcie proponowanej daty początkowej lub końcowej ------------------- //
function teacherDateClick() {
    $('.teacherStart').bind('click', function(){
        start = $(this).html();
        $('input#start').val(start);
        end = $('input#end').val();
        datesValidate(start, end);
        return false;
    });
    $('.teacherEnd').bind('click', function(){
        end = $(this).html();
        $('input#end').val(end);
        start = $('input#start').val();
        datesValidate(start, end);
        return false;
    });
}

function dateStartChange() {
    $('input#start').change('click', function(){
        var start = $(this).val();
        var end = $('input#end').val();
        datesValidate(start, end);
        return false;
    });
}
function dateEndChange() {
    $('input#end').change('click', function(){
        var start = $('input#start').val();
        var end = $(this).val();
        datesValidate(start, end);
        return false;
    });
}
function datesValidate(start, end) {
    $('div#error').html('').addClass('hide');
    if( start < $('#groupStart').html() )
      $('div#error').html('data początkowa nie może być wcześniejsza niż data początkowa grupy').removeClass('hide');
    if( end > $('#groupEnd').html() )
      $('div#error').html('data końcowa nie może być późniejsza niż data końcowa grupy').removeClass('hide');
    if( start > end )
      $('div#error').html('data początkowa nie może być późniejsza niż data końcowa').removeClass('hide');
    if( start == '' )
      $('div#error').html('data początkowa nie może być pusta').removeClass('hide');
    if( end == '' )
      $('div#error').html('data końcowa nie może być pusta').removeClass('hide');
}

function buttonChangeTeacherClick() {
    $('#changeTeacher').bind('click', function(){
        $('#addTeacherForm').attr('action', "http://localhost/school/grupa_nauczyciele/changeTeacher");
        $('#addTeacherForm').submit();
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    teacherDateClick();
    dateStartChange();
    dateEndChange();
    buttonChangeTeacherClick();

    var start = $('input#start').val();
    var end = $('input#end').val();
    datesValidate(start, end);
});