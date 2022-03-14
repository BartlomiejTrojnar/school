// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 18.09.2021 ------------------------ //
// ----------------------- wydarzenia na stronie wyświetlania nauczycieli ---------------------- //

function schoolYearChanged() {  // wybór roku szkolnego w polu select
    $('select[name="schoolYear_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/public/rok_szkolny/change/"+ $(this).val(),
            success: function() { window.location.reload(); },
        });
        return false;
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    schoolYearChanged();
});