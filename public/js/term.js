// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2020 ----------------------- //
// ------------------------ wydarzenia na stronie wyświetlania terminów ------------------------ //

function examDescriptionChanged() {  // wybór opis egzaminu w polu select 
    $('select[name="exam_description_id"]').bind('change', function() {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/opis_egzaminu/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}

function classroomChanged() {  // wybór sali w polu select
    $('select[name="classroom_id"]').bind('change', function() {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sala/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}

function sessionChanged() {  // wybór sesji maturalnej w polu select
    $('select[name="session_id"]').bind('change', function() {
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/sesja/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    examDescriptionChanged();
    classroomChanged();
    sessionChanged();
});