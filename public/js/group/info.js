// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 ------------------------ //
// ------------------------- wydarzenia na stronie informacji o grupie ------------------------- //

// ------------------------------- ZMIANA LICZBY GODZIN W GRUPIE ------------------------------- //
// ----------------------------- przypisanie operacji do kliknięć ------------------------------ //
function hourModificationClick() {
    $('#hourSubtract').click(function(){
        group_id = $(this).attr('data-group_id');
        url = $('td[data-group_id=' +group_id+ ']').attr('data-url');
        hourSubtract(group_id, url);
        return false;
    });
    $('#hourAdd').click(function(){
        group_id = $(this).attr('data-group_id');
        url = $('td[data-group_id=' +group_id+ ']').attr('data-url');
        hourAdd(group_id, url);
        return false;
    });
}

function hourSubtract(group_id, url) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url+'/hourSubtract',
        data: { group_id: group_id },
        success: function(result) {
			$('td[data-group_id=' +group_id+ '] span').html(result);
			return;
		},
		error: function(blad) { alert("Błąd: "+blad); }
	});
	return false;
}

function hourAdd(group_id, url) {
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: url+'/hourAdd',
        data: { group_id: group_id },
        success: function(result) {
			$('td[data-group_id=' +group_id+ '] span').html(result);
			return;
		},
		error: function(blad) { alert("Błąd: "+blad); }
	});
	return false;
}

// ------------------------------- USUWANIE NAUCZYCIELA Z GRUPY -------------------------------- //
// ----------------------------- przypisanie operacji do kliknięć ------------------------------ //
function teacherRemoveClick() {
    $('.teacherRemove').click('click', function(){
        var groupTeacher_id = $(this).attr("data-groupTeacher_id");
        var token = $(this).data("token");
        teacherRemove(groupTeacher_id, token);
        return false;
    });
}

function teacherRemove(groupTeacher_id=0, token='') {
    $.ajax({
        url: $('button[data-groupTeacher_id=' +groupTeacher_id+ ']').attr('data-url'),
        type: "DELETE",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {  $('div[data-groupTeacher_id=' +groupTeacher_id+ ']').remove();  },
        error: function() {  alert("Błąd: "+url);  },
    });
    return false;
}

// ---------------------------------- USUWANIE KLASY Z GRUPY ----------------------------------- //
// ----------------------------- przypisanie operacji do kliknięć ------------------------------ //
function gradeRemoveClick() {
    $('.gradeRemove').click(function(){
        var groupGrade_id = $(this).attr("data-groupGrade_id");
        var token = $(this).data("token");
        gradeRemove(groupGrade_id, token);
        return false;
    });
}

function gradeRemove(groupGrade_id=0, token='') {
    $.ajax({
        url: $('button[data-groupGrade_id=' +groupGrade_id+ ']').attr('data-url'),
        type: "DELETE",
        data: { "_token":token, },
        success: function() {  $('div[data-groupGrade_id=' +groupGrade_id+ ']').remove();  },
        error: function(result) {  alert("Błąd: "+result);  },
    });
    return false;
}

// --------------------------- kliknięcie przycisku "Przedłuż grupę" --------------------------- //
function groupExtensionClick() {
    $('button#groupExtension').bind('click', function(){
        var group_id = $("#groupExtensionId").val();
        var dateGroupExtension = $("#dateGroupExtension").val();
        if(dateGroupExtension == "") return false;
        $.ajax({
            type: "POST",
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "http://localhost/school/group/extension/",
            data: { group_id: group_id, dateGroupExtension: dateGroupExtension },
            success: function(result) { if(result==1) window.location.reload(); else alert(result); },
            error: function(result) { alert("Error: "+result); },
        });
        return false;
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    hourModificationClick();
    teacherRemoveClick();
    gradeRemoveClick();
    groupExtensionClick();
});