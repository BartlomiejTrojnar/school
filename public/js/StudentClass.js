// --------------------------------------- INNE OPERACJE ---------------------------------------- //
function klikanieDat() {
    $('a#date_start1').bind('click', function(){
        $('#date_start').val($(this).html());
        return false;
    });
    $('a#date_start2').bind('click', function(){
        $('#date_start').val($(this).html());
        return false;
    });
    $('a#date_end1').bind('click', function(){
        $('#date_end').val($(this).html());
        return false;
    });
    $('a#date_end2').bind('click', function(){
        $('#date_end').val($(this).html());
        return false;
    });
    $('a#date_end3').bind('click', function(){
        $('#date_end').val($(this).html());
        return false;
    });
}

function klikanieNumeru() {
    $('a#proposedNumer').bind('click', function(){
        $('#numer').val($(this).html());
        return false;
    });
    $('a#numerDecrease').bind('click', function(){
        $('#numer').val($('#numer').val()-1);
        return false;
    });
    $('a#numerIncrease').bind('click', function(){
        $('#numer').val($('#numer').val()-1+2);
        return false;
    });
}

// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
     klikanieDat();
     klikanieNumeru();
});