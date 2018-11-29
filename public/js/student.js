// ----------------------------- wybór roku szkolnego w polu select ----------------------------- //
function schoolYearChanged() {
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "GET",
            url: "http://localhost/szkola/public/rok_szkolny/"+ $(this).val() +"/change",
            data: { schoolYear_id: $(this).val() },
            success: function(result) { window.location.reload(); },
        });
        return false;
    });
}

// --------------------------------- wybór klasy w polu select ---------------------------------- //
function gradeChanged() {
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "GET",
            url: "http://localhost/szkola/public/klasa/"+ $(this).val() +"/change",
            data: { grade_id: $(this).val() },
            success: function(result) { window.location.reload(); },
        });
        return false;
    });
}

// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
    gradeChanged();
    schoolYearChanged();
});