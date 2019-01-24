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

function numberClick() {
    $('.studentClassProposedNumber').bind('click', function(){
        $('#number').val($(this).html());
        return false;
    });
    $('.numberDecrease').bind('click', function(){
        if($('#number').val() == '')  $('#number').val(1);
        $('#number').val($('#number').val()-1);
        if($('#number').val() < 1)  $('#number').val(1);
        return false;
    });
    $('.numberIncrease').bind('click', function(){
        if($('#number').val() == '')  $('#number').val(0);
        $('#number').val($('#number').val()-1+2);
        if($('#number').val() > 99)  $('#number').val(99);
        return false;
    });
}

function numberUpClick() {
    $('#studentClasses .numberUp').bind('click', function(){
        $('tr').css('background', '');
        var id = $(this).attr("data-id");
        var number = $('tr[data-id=' +id+ '] span.number').html()-1;
        $('tr[data-id=' + id + ']').fadeOut( 500 );
        if( idToReplace = findRowsToReplaceId(number) ) {
            replaceRowsWithChangeNumbers(id, idToReplace);
            updateRecordNumber(idToReplace, number+1);
        }
        else {
            setTableRowNumber(id, number);
        }
        updateRecordNumber(id, number);
        $('tr[data-id=' + id + ']').fadeIn( 500 ).css('background', '#552');
        return false;
    });
}

function numberDownClick() {
    $('#studentClasses .numberDown').bind('click', function(){
        $('tr').css('background', '');
        var id = $(this).attr("data-id");
        $('tr[data-id=' + id + ']').fadeOut( 500 );
        var number = $('tr[data-id=' +id+ '] span.number').html()-1+2;
        if( idToReplace = findRowsToReplaceId(number) ) {
            replaceRowsWithChangeNumbers(idToReplace, id);
            updateRecordNumber(idToReplace, number-1);
        }
        else {
            setTableRowNumber(id, number);
        }
        updateRecordNumber(id, number);
        $('tr[data-id=' + id + ']').fadeIn( 500 ).css('background', '#552');
        return false;
    });
}

function updateRecordNumber(id, number) {
    $.ajax({
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "http://localhost/szkola/public/klasy_ucznia/updateNumber",
        data: { id: id, number: number },
        success: function(result) { return; },
        error: function(result) { alert(result) },
    });
    return false;
}

function findRowsToReplaceId(number) {
    var count = 0, id = 0;
    $('tr[data-number=' +number+ ']:visible').each(function() {
        id = $(this).attr('data-id');
        count++;
    });
    if(count>1) return 0;
    return id;
}

function replaceRowsWithChangeNumbers(id, idToReplace) {
    var number = $('tr[data-id=' +id+ '] span.number').html();
    var tdNumber = $('tr[data-id=' +id+ '] td:first-child').html();

    setTimeout(function() {
         $('tr[data-id=' + idToReplace + ']').before( $('tr[data-id=' + id + ']').detach() );
         setTableRowNumber(idToReplace, number, tdNumber);
         setTableRowNumber(id, number-1, tdNumber-1);
    }, 500);
}

function setTableRowNumber(id, number, tdNumber=0) {
    $('tr[data-id=' + id + '] span.number').html(number);
    $('tr[data-id=' + id + ']').attr('data-number', number);
    if(tdNumber)
        $('tr[data-id=' + id + '] td:first-child').html(tdNumber);
}

function gradeChanged() {
    $('select[name="grade_id"]').bind('change', function(){
        getGradeDates($(this).val());
        return false;
    });
    if( !$.isEmptyObject( $('select[name="grade_id"]').html() ) && $('select[name="grade_id"]').val() != '0' )
      getGradeDates( $('select[name="grade_id"]').val() );
}
function getGradeDates(grade_id) {
    $('#date_start_row .proposedCell').html('');
    $('#date_end_row .proposedCell').html('');
    $.ajax({
        type: "GET",
        url: "http://localhost/szkola/public/klasa/"+grade_id+"/getDates",
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
    var lp = 0;
    $('table#studentClasses tr').each( function() {
        if( $(this).attr('data-start') > date_end || $(this).attr('data-end') < date_start)
          $(this).fadeOut( 1000 );
        else {
            $(this).fadeIn( 1000 );
            $('td:first-child', this).html(lp++);
        }
    });
}



// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
    klikanieDat();
    numberClick();
    gradeChanged();
    dateStartOrEndChanged();
    clickGradeButtons();
    numberUpClick();
    numberDownClick();
});