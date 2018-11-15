// --------------------------------------- INNE OPERACJE ---------------------------------------- //
function klikanieDat() {
    $('.studentClassDateStart').bind('click', function(){
        $('#date_start').val($(this).html());
        return false;
    });
    $('.studentClassDateEnd').bind('click', function(){
        $('#date_end').val($(this).html());
        return false;
    });
}

function klikanieNumeru() {
    $('.studentClassProposedNumber').bind('click', function(){
        $('#number').val($(this).html());
        return false;
    });
    $('.numerDecrease').bind('click', function(){
        if($('#number').val() == '')  $('#number').val(1);
        $('#number').val($('#number').val()-1);
        if($('#number').val() < 1)  $('#number').val(1);
        return false;
    });
    $('.numerIncrease').bind('click', function(){
        if($('#number').val() == '')  $('#number').val(0);
        $('#number').val($('#number').val()-1+2);
        if($('#number').val() > 99)  $('#number').val(99);
        return false;
    });
}

// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
     klikanieDat();
     klikanieNumeru();
});