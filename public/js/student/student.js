// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (III) maj 2020 ----------------------- //
// ------------------------ wydarzenia na stronie wyświetlania uczniów ------------------------- //

function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rok_szkolny/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
        });
        return false;
    });
}

function gradeChanged() {  // wybór klasy w polu select
    $('select[name="grade_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/klasa/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
        });
        return false;
    });
}

function dateViewChange() {     // po zmianie widocznej na stronie daty widoku
    $('#dateView').bind('blur', function() {
        //$('table#gradePlan td.lesson').html('?');
        // zapamiętanie wybranej daty widoku
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rememberDates",
            data: { dateView: $('#dateView').val() },
            success: function()  { window.location.reload(); },
        });
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChanged();
    schoolYearChanged();
    dateViewChange();
});