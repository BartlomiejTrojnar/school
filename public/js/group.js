// ---------------------------------- OPERACJE DOTYCZĄCE GRUPY ---------------------------------- //
// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) listopad 2018 --------------------- //

// ------------------------------- ZMIANA LICZBY GODZIN W GRUPIE -------------------------------- //
// ------------------------------ przypisanie operacji do kliknięć ------------------------------ //
function hourModificationClick()
{
    $('#hourSubtract').bind('click', function(){
        group_id = $(this).attr('data-group_id');
        url = $('td[data-group_id=' +group_id+ ']').attr('data-url');
        hourSubtract(group_id, url);
        return false;
    });
    $('#hourAdd').bind('click', function(){
        group_id = $(this).attr('data-group_id');
        url = $('td[data-group_id=' +group_id+ ']').attr('data-url');
        hourAdd(group_id, url);
        return false;
    });
}

function hourSubtract(group_id, url)
{
    $.ajax({
        type: "GET",
        url: url+'/hourSubtract/'+group_id,
        data: { group_id: group_id },
        success: function(result) {
			$('td[data-group_id=' +group_id+ '] span').html(result);
			return;
		},
		error: function(blad) { alert("Błąd: "+blad); }
	});
	return false;
}

function hourAdd(grupa, url) {
    $.ajax({
        type: "GET",
        url: url+'/hourAdd/'+group_id,
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
// ------------------------------ przypisanie operacji do kliknięć ------------------------------ //
function teacherRemoveClick()
{
    $('.teacherRemove').bind('click', function(){
        groupTeacher_id = $(this).attr('data-groupTeacher_id');
        teacherRemove(groupTeacher_id);
        return false;
    });
}

function teacherRemove(groupTeacher_id=0)
{
    url = $('button[data-groupTeacher_id=' +groupTeacher_id+ ']').attr('data-url');
    alert(url+' DOKOŃCZYĆ funkcję teacher Remove');
    return;
    $.ajax({
        type: "DELETE",
        url: url,
        data: { groupTeacher_id: groupTeacher_id },
        success: function(result) {
			$('div[data-groupTeacher_id=' +groupTeacher_id+ ']').remove();
			return;
		},
	});
	return false;
}

// ----------------------------------- załadowanie dokumentu ------------------------------------ //
$(document).ready(function()
{
    hourModificationClick();
    teacherRemoveClick();
});