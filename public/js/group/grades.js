// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 21.10.2022 ------------------------ //
// --------------------- wydarzenia na stronie wyświetlania klas w grupie ---------------------- //

// --------------------------- DODAWANIE LUB USUWANIE KLASY Z GRUPY ---------------------------- //
// ----------------------------- przypisanie operacji do kliknięć ------------------------------ //
function gradeClick() {
    $('#gradesTable button').bind('click', function(){
        if( $(this).hasClass("disabled") ) return false;
        if( $(this).hasClass("errorChecked") ) {
            $(this).addClass("disabled").removeClass("errorChecked");
            removeGradeFromGroup( $(this).data('grade') );
            unblockButtonsIfNoneSelected();
            return false;
        }

        if( $(this).hasClass("abled") ) {
            blockButtonsFromOtherYears( $(this).data('year') );
            addGradeToGroup( $(this).data('grade') );
            $(this).addClass("checked").removeClass("abled");
            return false;
        }

        if( $(this).hasClass("checked") ) {
            $(this).addClass("abled").removeClass("checked");
            removeGradeFromGroup( $(this).data('grade') );
            unblockButtonsIfNoneSelected();
            return false;
        }
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
    var buttonList = $('button.checked');
    if( buttonList.length < 1)  $('button[data-year]').removeClass('disabled').addClass("abled");
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeClick();
});