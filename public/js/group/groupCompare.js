// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (II) lipiec 2020 ----------------------- //
// ----------------------- wydarzenia na stronie porównywania grup ----------------------- //


// ------------------------ ZMIANA PARAMETRÓW FILTROWANIA W POLU SELECT ------------------------ //
// ------------------------------ wybór klasy w polu select ------------------------------- //
function gradeChange() {
    $('select[name="grade_id"]').bind('change', function(){
        var dateView = $('#dateView').val();
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/groupCompare/gradeChoice/",
            data: { grade_id: $(this).val(), dateView: dateView },
            success: function(result) {
                $("div#listOfStudentsForVerification").html(result);
            },
            error: function(result) { alert(result); window.location.reload(); },
        });
        return false;
    });
}
/*
// ------------------------------ wybór przedmiotu w polu select ------------------------------- //
function subjectChange() {
    $('select[name="subject_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/przedmiot/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}
// ------------------------------ wybór nauczyciela w polu select ------------------------------- //
function teacherChange() {
    $('select[name="teacher_id"]').bind('change', function(){
        $.ajax({
            type: "POST",
            url: "http://localhost/school/nauczyciel/change/"+ $(this).val(),
            success: function(result) { window.location.reload(); },
            error: function(result) { window.location.reload(); },
        });
        return false;
    });
}
*/

function groupDisplay(group_id, tag) {
    var dateView = $('#dateView').val();
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/groupCompare/displayGroup/",
        data: { group_id: group_id, dateView: dateView },
        success: function(result) {
            var arr = [];
            $( "div#listOfStudentsForVerification li" ).each(function() {
                arr.push( $(this).data("student_id") );
            });
            $("div#"+tag).html(result);
            $("div#"+tag+" li").each(function() {
                $(this).hide();
                if( jQuery.inArray( $(this).data("student_id"), arr )>0 )   {
                    $(this).show();
                    var groupCount = parseInt( $("div#listOfStudentsForVerification li[data-student_id="+ $(this).data("student_id") +"]").attr("data-groups_count") );
                    $( "div#listOfStudentsForVerification li[data-student_id="+ $(this).data("student_id") +"]" ).attr("data-groups_count", groupCount+1);
                }
            });
        },
        error: function(result) { alert("Error: "+result); },
    });
    return false;
}

function group1DisplayChange() {
    gradesForGroup1Change();
    $('select[name="groupChoice1"]').bind('change', function(){
        groupDisplay($(this).val(), "listOfStudentsForGroup1");
    });
}
function group2DisplayChange() {
    gradesForGroup2Change();
    $('select[name="groupChoice2"]').bind('change', function(){
        groupDisplay($(this).val(), "listOfStudentsForGroup2");
    });
}
function group3DisplayChange() {
    gradesForGroup3Change();
    $('select[name="groupChoice3"]').bind('change', function(){
        groupDisplay($(this).val(), "listOfStudentsForGroup3");
    });
}
function group4DisplayChange() {
    gradesForGroup4Change();
    $('select[name="groupChoice4"]').bind('change', function(){
        groupDisplay($(this).val(), "listOfStudentsForGroup4");
    });
}

// zmiana parametrów do wyświetlania grup w polach wyboru
function gradesForGroup1Change() {
    $('select[name="gradesForGroup1"]').bind('change', function(){
        displayGroupSelectField(1, $(this).val());
    });
}
function gradesForGroup2Change() {
    $('select[name="gradesForGroup2"]').bind('change', function(){
        displayGroupSelectField(2, $(this).val());
    });
}
function gradesForGroup3Change() {
    $('select[name="gradesForGroup3"]').bind('change', function(){
        displayGroupSelectField(3, $(this).val());
    });
}
function gradesForGroup4Change() {
    $('select[name="gradesForGroup4"]').bind('change', function(){
        displayGroupSelectField(4, $(this).val());
    });
}
function displayGroupSelectField(number, grade_id) {
    var dateView = $('#dateView').val();
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/grupa/selectField/",
        data: { number: number, grade_id: grade_id, dateView: dateView },
        success: function(result) { $('select[name="groupChoice'+number+'"]').html(result); },
        error: function(result) { alert("Error: "+result); },
    });
    return false;
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    gradeChange();
    group1DisplayChange();
    group2DisplayChange();
    group3DisplayChange();
    group4DisplayChange();
});