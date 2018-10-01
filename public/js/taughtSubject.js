/* (C) mgr inż. Bartłomiej Trojnar; (I) grudzień 2015 */
// ------------------------- OPERACJE W TLE STRONY dotyczące nauczanych przedmiotów ------------------------- //

/*
// ------------------------- DODAWANIE PRZEDMIOTU NA LISTĘ NAUCZANYCH ------------------------- //
// ------------------------- skrypt /nauczyciele/nauczyciel.php (widok przedmioty) ------------------------- //
function wpisz_przedmiot(dane, przedmiot) {		//wpisanie przedmiotu na listę nauczanych przez nauczyciela
	var nazwa = $('li[data-przedmiot='+przedmiot+']').html();
	$('li[data-przedmiot='+przedmiot+']').remove();
	nauczanie = '<li data-nauczanie="' +dane+'" data-przedmiot="' +przedmiot+ '">' + nazwa + '</li>';
	$('#nauczane ul' ).append(nauczanie);
	$('#nauczane li').attr('draggable', 'true');
	$('#nauczane li').bind('dragstart', function(event) {		//podniesienie przedmiotu
		var data = event.originalEvent.dataTransfer;
		data.setData("id", $(this).attr("data-nauczanie"));
		data.setData("prz", $(this).attr("data-przedmiot"));
		return true;
	});
}

// ------------------------- DODAWANIE NAUCZYCIELA NA LISTĘ NAUCZAJĄCYCH ------------------------- //
// ------------------------- skrypt /przedmioty/przedmiot.php (widok nauczyciele) ------------------------- //
function wpisz_nauczyciela(dane, idnauczyciela) {		//wpisanie nauczyciela na listę uczących przedmiotu
	var nauczyciel = $('li[data-nauczyciel='+ idnauczyciela +']').html();
	$('li[data-nauczyciel='+ idnauczyciela +']').remove();
	var nauczanie = '<li data-nauczanie="' +dane+'" data-nauczyciel="' +nauczyciel+ '">' + nauczyciel + '</li>';
	$('#lista_nauczajacych ul' ).append(nauczanie);
	$('#lista_nauczajacych li').attr('draggable', 'true');
	$('#lista_nauczajacych li').bind('dragstart', function(event) {		//podniesienie nauczyciela
		var data = event.originalEvent.dataTransfer;
		data.setData("id", $(this).attr("data-nauczanie"));
		data.setData("nau", $(this).attr("data-nauczyciel"));
		return true;
	});
}


// ------------------------- USUWANIE NAUCZANIA PRZEDMIOTU ------------------------- //
// ------------------------- skrypt /nauczyciele/nauczyciel.php (widok przedmioty) ------------------------- //
function usun_przedmiot(nauczanie) {		//usuwanie przedmitu z listy nauczanych przez nauczyciela
	var przedmiot = $('li[data-nauczanie='+ nauczanie +']').attr('data-przedmiot');
	var nazwa = $('li[data-nauczanie='+ nauczanie +']').html();
	$('li[data-nauczanie='+nauczanie+']').remove();
	var	prz = '<li data-przedmiot="' +przedmiot+ '">' +nazwa+ '</li>';
	$('#nienauczane ul' ).append(prz);
	$('#nienauczane li').attr('draggable', 'true');
	$('#nienauczane li').bind('dragstart', function(event) {		//podniesienie przedmiotu
		var data = event.originalEvent.dataTransfer;
		data.setData("prze", $(this).attr("data-przedmiot"));
		return true;
	});
}

// ------------------------- skrypt /przedmioty/przedmiot.php (widok nauczyciele) ------------------------- //
function usun_nauczyciela(nauczanie) {		//usuwanie nauczyciela z listy uczących przedmiotu
	var idnauczyciela = $('li[data-nauczanie='+ nauczanie +']').attr('data-nauczyciel');
	var nauczyciel = $('li[data-nauczanie='+ nauczanie +']').html();
	$('li[data-nauczanie='+nauczanie+']').remove();
	var	nau = '<li data-nauczyciel="' +nauczyciel+ '">' +nauczyciel+ '</li>';
	$('#inni_nauczyciele ul' ).append(nau);
	$('#inni_nauczyciele li').attr('draggable', 'true');
	$('#inni_nauczyciele li').bind('dragstart', function(event) {		//podniesienie nauczyciela
		var data = event.originalEvent.dataTransfer;
		data.setData("nau", $(this).attr("data-nauczyciel"));
		return true;
	});
}
*/

function addTeaching(subject_id, teacher_id) {
    var url = $('div#url').text();
    var token = $('div#token input').val();
    var subjectName = $('li[data-subject-id=' +subject_id+ ']').text();
    var li_1 = '<li class="list-group-item active" type="button" data-subject-name="' +subjectName+ '" data-subject-id="';
    var li_2 = '" data-taught-subject-id="';;
    var li_3 = '">' +subjectName+ '<span class="url">http://localhost/szkola/public/nauczany_przedmiot/delete/';
    var li_4 = '</span></li>';

    $.ajax({
        url: url,
        method: "post",
        data: { subject_id: subject_id, teacher_id: teacher_id, _token: token },
        success: function(last_id) {
            $('li[data-subject-id=' +subject_id+ ']').remove();
            var li = li_1 + subject_id + li_2 +last_id+ li_3 + last_id + li_4;
            $('#taughtSubjectsList ul').append(li);
            $('#taughtSubjectsList li').last().attr('data-subject-id', string(subject_id));
        },
        error: function(blad) { alert('Błąd'+blad); }
    });
}

function deleteTeaching(url, id, subject_id, subjectName) {
    $.ajax({
        url: url,
        method: "get",
        data: { id: id },
        success: function(dane) {
            $('li[data-taught-subject-id='+id+']').remove();
            $('#subjectsList ul').append( '<li class="list-group-item" data-subject-id="' +subject_id+ '" type="button">' +subjectName+ '</li>' );
        },
        error: function(blad) { alert(blad); }
    });
}

function addTeachingDragDrop() {
    $('#subjectsList li').attr('draggable', 'true');
    $('#subjectsList li').bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        data.setData("subject_id", $(this).attr("data-subject-id"));
        data.setData("teacher_id", $(this).attr("data-teacher-id"));
        return true;
    });
    $('#taughtSubjectsList').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        addTeaching(data.getData('subject_id'), data.getData('teacher_id'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#taughtSubjectsList').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function deleteTeachingDragDrop() {
    $('#taughtSubjectsList li').attr('draggable', 'true');
    $('#taughtSubjectsList li').bind('dragstart', function(event) {	// podniesienie elementu
        var data = event.originalEvent.dataTransfer;
        var id = $(this).attr("data-taught-subject-id");
        var url = $('li[data-taught-subject-id='+id+'] span').html();
        data.setData("taughtSubject_id", id);
        data.setData("url", url);
        data.setData("subject_id", $(this).attr("data-subject-id"));
        data.setData("subjectName", $(this).attr("data-subject-name"));
        return true;
    });
    $('#subjectsList').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        deleteTeaching(data.getData('url'), data.getData('taughtSubject_id'), data.getData('subject_id'), data.getData('subjectName'));
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#subjectsList').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}


/*
	// ------------------------- skrypt /przedmioty/przedmiot.php (widok nauczyciele) ------------------------- //
	$('#lista_nauczajacych').bind('drop', function(event) {			//upuszczenie nauczyciela
		var data = event.originalEvent.dataTransfer;
		dodaj_nauczanie_przedmiotu(data.getData('nau'), $('#idprzedmiotu').html(), true);
		if(event.preventDefault) event.preventDefault();
		return false;
	});
	$('#lista_nauczajacych').bind('dragover', function(event) {
		if(event.preventDefault) event.preventDefault();
		return false;
	});
*/

	// ------------------------- USUWANIE NAUCZANIA PRZEDMIOTU ------------------------- //
	// ------------------------- skrypt /nauczyciele/nauczyciel.php (widok przedmioty) ------------------------- //
/*
	// ------------------------- skrypt /przedmioty/przedmiot.php (widok nauczyciele) ------------------------- //
	$('#lista_nauczajacych li').attr('draggable', 'true');
	$('#lista_nauczajacych li').bind('dragstart', function(event) {		//podniesienie nauczania
		var data = event.originalEvent.dataTransfer;
		data.setData("id", $(this).attr("data-nauczanie"));
		data.setData("nau", $(this).attr("data-nauczyciel"));
		return true;
	});
	$('#inni_nauczyciele').bind('drop', function(event) {			//upuszczenie nauczania
		var data = event.originalEvent.dataTransfer;
		usun_nauczanie_przedmiotu(data.getData('id'), data.getData('nau'));
		usun_nauczyciela(data.getData('id'));
		if(event.preventDefault) event.preventDefault();
		return false;
	});
	$('#inni_nauczyciele').bind('dragover', function(event) {
		if(event.preventDefault) event.preventDefault();
		return false;
	});
	return true;
}

*/
// ------------------------- załadowanie dokumentu ------------------------- //
$(document).ready(function() {
    deleteTeachingDragDrop();
    addTeachingDragDrop();
});