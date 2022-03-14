// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2020 ----------------------- //
// ----------------------- wydarzenia na stronie wyświetlania klas w grupie ----------------------- //


// ---------------------------- DODAWANIE LUB USUWANIE KLASY Z GRUPY ---------------------------- //
// ------------------------------ przypisanie operacji do kliknięć ------------------------------ //
function gradeClick() {
    $('#grades_list button').bind('click', function(){
        var isChecked = $(this).data('checked');
        if(isChecked) {
            removeGradeFromGroup( $(this).data('grade') );
            unblockButtonsIfNoneSelected();
        }
        else {
            //$(this).data('checked', 1);
            addGradeToGroup( $(this).data('grade') );
            blockButtonsFromOtherYears( $(this).data('year') );
        }
        return false;
    });
}

function addGradeToGroup(grade_id)  {
    var group_id = $('input[name="group_id"]').val();
    $.ajax({
        type: "POST",
        url: "http://localhost/school/public/grupa_klasy/addGrade",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { "group_id": group_id, "grade_id": grade_id },
        success: function(result) {
            $('button[data-grade="' +grade_id+ '"]').removeClass('btn-info');
            $('button[data-grade="' +grade_id+ '"]').addClass('btn-danger');
            $('button[data-grade="' +grade_id+ '"]').attr('data-checked', 1);
            $('button[data-grade="' +grade_id+ '"]').attr('data-id', result);
            $('button[data-grade="' +grade_id+ '"] i').removeClass('fa-plus');
            $('button[data-grade="' +grade_id+ '"] i').addClass('fa-remove');
            $('button[data-grade="' +grade_id+ '"]').bind('click', function(){
                removeGradeFromGroup( $(this).data('grade') );
                return false;
            });
            return;
        },
        error: function(result) {  alert("Błąd: "+result);  },
    });
}

function removeGradeFromGroup(grade_id)  {
    var group_id = $('input[name="group_id"]').val();
    $.ajax({
        type: "POST",
        url: "http://localhost/school/public/grupa_klasy/removeGrade",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { "group_id": group_id, "grade_id": grade_id },
        success: function(result) {
            $('button[data-grade="' +grade_id+ '"]').addClass('btn-info');
            $('button[data-grade="' +grade_id+ '"]').removeClass('btn-danger');
            $('button[data-grade="' +grade_id+ '"]').attr('data-checked', 0);
            $('button[data-grade="' +grade_id+ '"]').attr('data-id', 0);
            $('button[data-grade="' +grade_id+ '"] i').removeClass('fa-remove');
            $('button[data-grade="' +grade_id+ '"] i').addClass('fa-plus');
            $('button[data-grade="' +grade_id+ '"]').bind('click', function() {
                addGradeToGroup( $(this).data('grade') );
                return false;
            });
            return;
        },
        error: function(result) {  alert("Błąd: "+result);  },
    });
}

function blockButtonsFromOtherYears(year)  {  $('button[data-year!="' + year + '"]').addClass('disabled'); }

function unblockButtonsIfNoneSelected()  {
    var buttonList = $('button[data-checked="1"]');
    if( buttonList.length <= 1)  $('button[data-year]').removeClass('disabled');
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeClick();
});