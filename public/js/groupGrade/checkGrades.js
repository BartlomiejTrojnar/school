// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.04.2023 ------------------------ //
/*
    skrypt oznacza klasy przypisane do grupy:
    1. brak uczniów danej klasy w grupie - class 'no-students' (początkowa domyślna klasa css)
    2. uczniowie klasy w innym czasie w grupie - class 'inactive'
    3. co najmniej jeden uczeń aktualnie w grupie - class 'active'
*/

// zaznaczenie klas aktywnych i nieaktywnych
function checkGrades() {
    var dateView = $('#dateView').val();
    var countActualStudents = 0, countAllStudents=0, countActualGradesStudents=0;
    var studentGroupStart, studentGroupEnd, studentGradeStart, studentGradeEnd, grade_id;
    // dla każdego ucznia w grupie...
    $('#groupStudents li.student').each(function() {
        countAllStudents++;
        // sprawdzenie czy uczeń należy do grupy dla wybranej daty
        studentGroupStart = $(this).children('.studentGroupStart').html();
        studentGroupEnd = $(this).children('.studentGroupEnd').html();
        if(studentGroupStart <= dateView && studentGroupEnd>=dateView)  {
            countActualStudents++;
            // dla każdej klasy do której należał uczeń...
            $(this).children('ul').children('li').each(function() {
                studentGradeStart = $(this).children('.studentGradeStart').html();
                studentGradeEnd = $(this).children('.studentGradeEnd').html();
                grade_id = $(this).children('.grade_id').html();
                countActualGradesStudents += markGrade(grade_id, dateView, studentGradeStart, studentGradeEnd);
            });
        }
    });
}

function markGrade(grade_id, dateView, studentGradeStart, studentGradeEnd) {
    // jeżeli uczeń aktualnie należy do klasy
    if(studentGradeStart <= dateView && studentGradeEnd >= dateView) {
        $('a[data-grade_id="' +grade_id+ '"]').removeClass('no-students').removeClass('inactive').addClass('active');
        $('span[data-grade_id="' +grade_id+ '"]').removeClass('no-students').removeClass('inactive').addClass('active');
        $('aside[data-grade_id="' +grade_id+ '"]').remove();
        return 1;
    }
    // jeżeli aktualnie nie należy do klasy
    else {
        if( $('a[data-grade_id="' +grade_id+ '"]').hasClass('no-students') ) {
            $('a[data-grade_id="' +grade_id+ '"]').removeClass('no-students').addClass('inactive');
            $('aside[data-grade_id="' +grade_id+ '"]').html('uczniowie w innym czasie w grupie');
        }
        if( $('span[data-grade_id="' +grade_id+ '"]').hasClass('no-students') )
            $('span[data-grade_id="' +grade_id+ '"]').removeClass('no-students').addClass('inactive');
        return 0;            
    }
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    checkGrades();
});