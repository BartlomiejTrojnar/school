// ----------------------- (C) mgr inż. Bartłomiej Trojnar; (I) maj 2020 ----------------------- //
// ------------ wydarzenia na stronie ustalania kolejnośc nauczycieli na wydrukach ------------- //

function dragTeacher() {  // podnoszenie pola z nauczycielem
    $('div.teacher').attr('draggable', 'true');
    $('div.teacher').bind('dragstart', function(event) {
        var data = event.originalEvent.dataTransfer;
        data.setData('teacher', $(this).data('teacher'));
        data.setData('oldOrder', $(this).data('order'));
        return true;
    });
}

function dropTeacher() {  // opuszczenie nauczyciela na polu kolejności
    $('#printOrder li').bind('drop', function(event) {
        var data = event.originalEvent.dataTransfer;
        var order = $(this).data('order');
        var teacher = data.getData('teacher');
        var oldOrder = data.getData('oldOrder');
        if(setPrintOrder(teacher, order))  moveTeacher(teacher, order, oldOrder);
        if(event.preventDefault) event.preventDefault();
        return false;
    });
    $('#printOrder li').bind('dragover', function(event) {
        if(event.preventDefault) event.preventDefault();
        return false;
    });
}

function setPrintOrder(teacher, order) {  // zapamiętanie kolejności nauczyciela w bazie danych
    $.ajax({
        type: "POST",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        url: "http://localhost/school/nauczyciel/setPrintOrder",
        data: { teacher_id: teacher, order: order },
        success: function(result) {
            if(result) return true;
            return false;
        },
        error: function(result) { alert('Błąd: teacherPrintOrder.js - funkcja setPrintOrder'); alert(result); return false; }
    });
    return true;
}

function moveTeacher(teacher, order, oldOrder) {  // przesunięcie nauczyciela
    var name = $('div[data-teacher="'+teacher+'"]').html();
    $('div[data-teacher="'+teacher+'"]').remove();
    var div = '<div class="teacher" data-teacher="'+teacher+'" data-order="'+order+'">' + name + '</div>';
    $('li[data-order="'+order+'"]').append(div);
    dragTeacher();
}

// ---------------------- wydarzenia wywoływane po załadowaniu dokumnetu ----------------------- //
$(document).ready(function() {
    dragTeacher();
    dropTeacher();
});