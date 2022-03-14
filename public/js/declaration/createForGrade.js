// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 ------------------------ //
// --------------- wydarzenia na stronie dodawania deklaracji dla uczniów klasy ---------------- //

function gradeChanged() {       // po zmianie klasy w polu select
    $('select[name="grade_id"]').bind('change', function(){
        refreshStudentsList($(this).val());
        return false;
    });
}

function refreshStudentsList(grade_id) {    // odświeżenie listy uczniów
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/public/deklaracja/getListStudentWithoutDeclarationForGrade",
        data: { grade_id: grade_id },
        success: function(result) { $('#studentsList').html(result); },
        error: function() { $('#studentsList').html('Błąd ładowania listy uczniów.'); },
    });
    return false;
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChanged();
    refreshStudentsList( $('select[name="grade_id"]').val() );
});