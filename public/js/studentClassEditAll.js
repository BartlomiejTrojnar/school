function confirmAllDateStartClick() {
    $('#confirmAllDateStart').bind('click', function(){
        var isChecked = toggleChecked('#confirmAllDateStart', '.confirmationDateStart');
        setTheReadonlyFieldAttribute('date_start', isChecked);
    });
}

function confirmAllDateEndClick() {
    $('#confirmAllDateEnd').bind('click', function(){
        var isChecked = toggleChecked('#confirmAllDateEnd', '.confirmationDateEnd');
        setTheReadonlyFieldAttribute('date_end', isChecked);
    });
}

function confirmAllNumberClick() {
    $('#confirmAllNumber').bind('click', function(){
        var isChecked = toggleChecked('#confirmAllNumber', '.confirmationNumber');
        setTheReadonlyFieldAttribute('number', isChecked);
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

function setTheReadonlyFieldAttribute(tagName, value) {
    $('input[name^=' +tagName+ ']').each( function() {
        $(this).prop('readonly', value);
    });
}

function enterDateStartClick() {
    $('#enterDateStart').bind('click', function(){
        var dateToEnter = $('#propositionDateStart').val();
        $('input[name^="date_start"]').each( function() {
            if( !$(this).prop("readonly") )
              $(this).val(dateToEnter);
        });
        return false;
    });
}

function enterDateEndClick() {
    $('#enterDateEnd').bind('click', function(){
        var dateToEnter = $('#propositionDateEnd').val();
        $('input[name^="date_end"]').each( function() {
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
            var id = $(this).attr('name').substr(23);
            var isChecked = $(this).prop('checked');
            var tagName = "date_start" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };
        if( $(this).hasClass('confirmationDateEnd') ) {
            var id = $(this).attr('name').substr(21);
            var isChecked = $(this).prop('checked');
            var tagName = "date_end" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };
        if( $(this).hasClass('confirmationNumber') ) {
            var id = $(this).attr('name').substr(19);
            var isChecked = $(this).prop('checked');
            var tagName = "number" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };
        if( $(this).hasClass('confirmationComments') ) {
            var id = $(this).attr('name').substr(21);
            var isChecked = $(this).prop('checked');
            var tagName = "comments" + id;
            setTheReadonlyFieldAttribute(tagName, isChecked);
        };

    });
}

// ----------------------------------- ZAŁADOWANIE DOKUMENTU ------------------------------------ //
$(document).ready(function() {
    confirmAllDateStartClick();
    confirmAllDateEndClick();
    confirmAllNumberClick();
    confirmAllCommentsClick();
    enterDateStartClick();
    enterDateEndClick();
    enterCommentsClick();
    checkboxClick();
});