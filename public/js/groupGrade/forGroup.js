// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 28.06.2023 ------------------------ //
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
    alert(grade_id);
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
            var name = $('input[name="name'+grade_id+'"]').val();
            updateGroupGradeName(group_id, grade_id, name);
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

// ------------------------------------ ZMIANA NAZWY GRUPY ------------------------------------- //
function changeGroupGradeName() {
    $('#gradesTable input').bind('change', function(){
        var name = $(this).val();
        var grade_id = parseInt($(this).attr('name').substr(4));
        var group_id = $('input[name="group_id"]').val();
        updateGroupGradeName(group_id, grade_id, name);
    });
}

function updateGroupGradeName(group_id, grade_id, name) {
    $.ajax({
        type: "POST",
        url: "http://localhost/school/grupa_klasy/changeName",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: { "group_id": group_id, "grade_id": grade_id, name: name },
        success: function() { 
            $('input[name="name'+grade_id+'"]').animate({ 'opacity': '0' }, 200).animate({ 'opacity': '1' }, 200).animate({ 'opacity': '0' }, 200).animate({ 'opacity': '1' }, 200);
        },
        error: function(result) {  alert("Błąd: "+result);  },
    });
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeClick();
    changeGroupGradeName();
});