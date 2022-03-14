// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2020 ----------------------- //
// ----------------------- wydarzenia na stronie wyświetlania deklaracji ----------------------- //

function dateViewChange() {  // po zmianie widocznej na stronie daty widoku
    $('#dateView').bind('blur', function() {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rememberDates",
            data: { dateView: $('#dateView').val() },
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    dateViewChange();
});