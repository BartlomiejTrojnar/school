// ---------------------------------- OPERACJE DOTYCZĄCE GRUPY ---------------------------------- //
// -------------------- (C) mgr inż. Bartłomiej Trojnar; (II) wrzesień 2017 -------------------- //

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

// ----------------------------------- załadowanie dokumentu ------------------------------------ //
$(document).ready(function()
{
    hourModificationClick();
});