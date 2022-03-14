// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 01.09.2021 ------------------------ //
// ------------- wydarzenia na stronie edytowania wielu rekordów dla klas uczniów -------------- //

function confirmAllDateStartClick() {
    $('#confirmAllDateStart').bind('click', function(){
        var isChecked = toggleChecked('#confirmAllDateStart', '.confirmationDateStart');
        setTheReadonlyFieldAttribute('dateStart', isChecked);
    });
}

function confirmAllDateEndClick() {
    $('#confirmAllDateEnd').bind('click', function(){
        var isChecked = toggleChecked('#confirmAllDateEnd', '.confirmationDateEnd');
        setTheReadonlyFieldAttribute('dateEnd', isChecked);
    });
}

function confirmAllCommentsClick() {
    $('#confirmAllComments').bind('click', function(){
        var isChecked = toggleChecked('#confirmAllComments', '.confirmationComments');
        setTheReadonlyFieldAttribute('comments', isChecked);
    });
}

function toggleChecked(tagId, tagClass) {
    if( $(tagId).prop('checked') ) {
        $(tagId).prop('checked', true);
        $(tagClass).prop('checked', true);
        return true;
    }
    else {
        $(tagId).prop('checked', false);
        $(tagClass).prop('checked', false);
        return false;
    }
}

function enterDateStartClick() {
    $('#enterDateStart').bind('click', function(){
        var dateToEnter = $('#propositionDateStart').val();
        $('input[name^="dateStart"]').each( function() {
            if( !$(this).prop("readonly") )   $(this).val(dateToEnter);
        });
        return false;
    });
}

function enterDateEndClick() {
    $('#enterDateEnd').bind('click', function(){
        var dateToEnter = $('#propositionDateEnd').val();
        $('input[name^="dateEnd"]').each( function() {
            if( !$(this).prop("readonly") )
              $(this).val(dateToEnter);
        });
        return false;
    });
}

function enterCommentsClick() {
    $('#enterComments').bind('click', function(){
        var commentsToEnter = $('#propositionComments').val();
        $('input[name^="comments"]').each( function() {
            if( !$(this).prop("readonly") )
              $(this).val(commentsToEnter);
        });
        return false;
    });
}

function checkboxClick() {
    $('input[type="checkbox"]').bind('click', function(){
        if( $(this).hasClass('confirmationDateStart') ) {
            var id = $(this).attr('name').substr(21);
            var isChecked = $(this).prop('checked');
            var tagName = "dateStart" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };
        if( $(this).hasClass('confirmationDateEnd') ) {
            var id = $(this).attr('name').substr(19);
            var isChecked = $(this).prop('checked');
            var tagName = "dateEnd" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };
        if( $(this).hasClass('confirmationComments') ) {
            var id = $(this).attr('name').substr(20);
            var isChecked = $(this).prop('checked');
            var tagName = "comments" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };
    });
}

function setTheReadonlyFieldAttribute(tagName, value) {
    $('input[name^=' +tagName+ ']').each( function() {
        $(this).prop('readonly', value);
    });
}


// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    confirmAllDateStartClick();
    confirmAllDateEndClick();
    confirmAllCommentsClick();
    enterDateStartClick();
    enterDateEndClick();
    enterCommentsClick();
    checkboxClick();
});