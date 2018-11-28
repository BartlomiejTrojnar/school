// --------------------------------------- INNE OPERACJE ---------------------------------------- //
function gradeChanged() {
    $('select[name="grade_id"]').bind('change', function(){
        getGradeStudents($(this).val());
        return false;
    });
}
function getGradeStudents(grade_id) {
    $.ajax({
        type: "GET",
        url: "http://localhost/szkola/public/klasa/"+grade_id+"/change",
        data: { grade_id: grade_id },
        success: function(result) { window.location.reload(); },
    });
    return false;
}

// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
    gradeChanged();
});