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

function gradeChanged() {
    $('select[name="grade_id"]').bind('change', function(){
        getGradeDates($(this).val());
        return false;
    });
}
function getGradeDates(grade_id) {
    $('#date_start_row .proposedCell').html('');
    $('#date_end_row .proposedCell').html('');
    $.ajax({
        type: "GET",
        url: "http://localhost/szkola/public/klasa/getDates/"+grade_id,
        data: { grade_id: grade_id },
        success: function(result) {
			$.each(result, function(index, value) {
               if(index % 2 == 0) {
                   $('#date_start_row .proposedCell').append('<button class="btn btn-success studentClassDateStart">'+value+'</button>');
               }
               else {
                   $('#date_end_row .proposedCell').append('<button class="btn btn-success studentClassDateEnd">'+value+'</button>');
               }
               klikanieDat();
            });
			return;
		},
        error: function(result) { alert(result) },
	});
	return false;
}

function clickGradeButtons() {
    $('#gradeButtons a').bind('click', function(){
        var year = $(this).attr('data-year');
        $('input#date_start').val( year-1+'-09-01' );
        $('input#date_end').val( year+'-08-31' );
        verifyAndDisplayStudents( $('input#date_start').val(), $('input#date_end').val() );
        return false;
    });
}
function dateStartOrEndChanged() {
    $('input#date_start').bind('change', function(){
        verifyAndDisplayStudents( $(this).val(), $('input#date_end').val() );
        return false;
    });
    $('input#date_end').bind('change', function(){
        verifyAndDisplayStudents( $('input#date_start').val(), $(this).val() );
        return false;
    });
}
function verifyAndDisplayStudents(date_start, date_end) {
    $('table#studentClasses tr').each( function() {
        if( $(this).attr('data-start') > date_end || $(this).attr('data-end') < date_start)
          $(this).hide();
        else $(this).show();
    });
}



// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
    klikanieDat();
    klikanieNumeru();
    gradeChanged();
    dateStartOrEndChanged();
    clickGradeButtons();
});