// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2020 ----------------------- //
// ------------------- wydarzenia na stronie dodawnia wielu uczniów do klasy ------------------- //
/*

// --------------------------------------- INNE OPERACJE ---------------------------------------- //
function addStudentDrag(indicatorCSS) {
    $(indicatorCSS).attr('draggable', 'true');
    $(indicatorCSS).bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData("student_id", $(this).data("student_id"));
        return true;
    });
}

function addStudentDrop(indicatorCSS) {
    $(indicatorCSS).bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        addStudent(data.getData('student_id'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $(indicatorCSS).bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function addStudent(student_id) {
    var grade_id = $('input[name="grade_id"]').val();
    var dateStart = $('input[name="dateStart"]').val();
    var dateEnd = $('input[name="dateEnd"]').val();

    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/klasy_ucznia",
        data: { student_id: student_id, grade_id: grade_id, dateStart: dateStart, dateEnd: dateEnd, confirmation_dateStart: 1, confirmation_dateEnd: 0 },
        success: function(result) {
            var student_li = $('li[data-student_id="'+student_id+'"]').html();
            $('#studentGradesList ol').append('<li>'+student_li+'</li>').fadeIn( 1500 );
            $('li[data-student_id="'+student_id+'"]').remove();
             //window.location.reload();
        },
        error: function(result) { alert("błąd -> "+result); //window.location.reload();
         },
    });
}

function yearChanged() {
    $('input[name="year_of_birth"]').bind('change', function(){
        verifyAndDisplayStudents( $('input#year_of_birth').val() );
        return false;
    });
}

function verifyAndDisplayStudents(yearOfBirth) {
    $('div#studentsList li').each( function() {
        if( $(this).attr('data-pesel').substr(0,2) != yearOfBirth.substr(2) )  $(this).fadeOut( 1500 );
        else $(this).fadeIn( 1500 );
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    addStudentDrag('#studentsList li');
    addStudentDrop('#studentGradesList');
    yearChanged();
    verifyAndDisplayStudents( $('input#year_of_birth').val() );
});
*/