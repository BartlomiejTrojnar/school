// -------------------- (C) mgr inż. Bartłomiej Trojnar; (III) listopad 2020 -------------------- //
// ----------------------- wydarzenia na stronie wyświetlania oceny zadań ----------------------- //

function buttonValueToEnterClick() {  // kliknięcie w przycisk wprowadzania proponowanej daty
    $('button#buttonValueToEnterClick').bind('click', function(){
        var termDate = $('input#valueToEnter').val();
        $('.unlocked .valueInput').val(termDate);
    });
}

function buttonStatusClick() {  // kliknięcie w przycisk blokady
    $('table').delegate('.unlocked .status', 'click', function(){
        $(this).html('<i class="fa fa-lock"></i>');
        var taskRating = $(this).attr('data-taskRating');
        $('tr[data-taskRating="' +taskRating+ '"] input').attr('readonly', 'readonly');
        $('tr[data-taskRating="' +taskRating+ '"]').removeClass('unlocked').addClass('locked');
    });

    $('table').delegate('.locked .status', 'click', function(){
        $(this).html("<i class='fas fa-hand-point-left'></i>");
        var taskRating = $(this).attr('data-taskRating');
        $('tr[data-taskRating="' +taskRating+ '"] input').removeAttr('readonly');
        $('tr[data-taskRating="' +taskRating+ '"]').removeClass('locked').addClass('unlocked');
    });
}

function pointsChanged() {  // zmiana ilości punktów -> zmiana procent punktów
    $('input[name="points"]').bind('change', function(){
        var percent = $(this).val() / $('#maxPoints').html() *100;
        $('#percent').html('('+percent+'%)');
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    pointsChanged();
    buttonValueToEnterClick();
    buttonStatusClick();
});