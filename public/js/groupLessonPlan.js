// --------------------------- OPERACJE DOTYCZĄCE PLANU LEKCJI GRUPY ---------------------------- //
// -------------------- (C) mgr inż. Bartłomiej Trojnar; (III) listopad 2018 -------------------- //

// --------------------------------- DODAWANIE LEKCJI DLA GRUPY --------------------------------- //
// ------------------------- kliknięcie znaku + na planie lekcji grupy -------------------------- //
function addLessonPlanClick()
{
    $('table.lessonPlan img.hour').bind('click', function(){
        var hour_id = $(this).attr('data-hour_id');
        addLessonPlan( hour_id );
        return false;
    });
}

function addLessonPlan(hour_id) {
    var group_id = $('#group_id').html();
    var url = $('#url').html() + '/' + group_id + '/' + hour_id;
    $.ajax({
        type: "GET",
        url: url,
        data: { group_id: group_id, hour_id: hour_id },
        success: function(result) {
			alert(result);
			return;
		},
		error: function(blad) { alert("Błąd: "+blad); }
	});
	return false;
}

// ------------------------------- WYSZUKIWANIE LEKCJI DLA GRUPY -------------------------------- //
function findLessons() {
    var url = $('#url').html();
    url = url.substr(0, url.length-10);
    var group_id = $('#group_id').html();
    for(g = 1; g<=45; g++)  {
        findLesson(group_id, g, url);
    }
    return;
}

function findLesson(group_id, g, url) {
    $.ajax({
        type: "GET",
        url: url+"/findLesson/"+ group_id +"/"+ g,
        data: {	hour_id: g },
        success: function(result) {
             $('td[data-hour_id=' +g+ ']').append(result);
        },
        error: function(result) { alert("Błąd: "+blad); }
    });
    return;
}

// ----------------------------------- załadowanie dokumentu ------------------------------------ //
$(document).ready(function()
{
    findLessons();
    addLessonPlanClick();
});