// ---------------------- (C) mgr inż. Bartłomiej Trojnar; (I) luty 2021 ---------------------- //
// ------------------------ wydarzenia na stronie wyświetlania uczniów ------------------------- //

function studentCreateClick() {     // po zmianie widocznej na stronie daty widoku
    $('#studentCreate').click(function() {
        $('table#students tr.create').hide();
        $('table#students tr#formStudentCreate').show(500);
        $('tr#formStudentCreate input[name="first_name"').focus();
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    studentCreateClick();
});