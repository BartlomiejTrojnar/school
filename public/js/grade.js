// ----------------------------- wybór szkoły w polu select ----------------------------- //
function schoolChanged() {
    $('select[name="school_id"]').bind('change', function(){
        $.ajax({
            type: "GET",
            url: "http://localhost/szkola/public/szkola/"+ $(this).val() +"/change",
            data: { school_id: $(this).val() },
            success: function(result) { window.location.reload(); },
        });
        return false;
    });
}

// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
    schoolChanged();
});