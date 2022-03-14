<tr class="editRowForBookOfStudent" data-book_of_student_id="{{$bookOfStudent->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 05.01.2022 *********************** -->
   <form action="{{ route('ksiega_uczniow.update', $bookOfStudent->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <label for="number">numer</label>
         <input type="number" name="number" min="1" size="5" required value="{{$bookOfStudent->number}}" />
      </td>
      <td colspan="2">
         <label for="school_id">szkoła</label>
         <?php  print_r($schoolSelectField);  ?>
         <input type="hidden" name="student_id" value="{{$bookOfStudent->student_id}}" />
      </td>
      <td colspan="2" class="c">
         <button class="update btn btn-primary" data-book_of_student_id="{{$bookOfStudent->id}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary">anuluj</button>
         <button class="destroy btn btn-primary" data-book_of_student_id="{{$bookOfStudent->id}}">usuń</button>
      </td>
      </form>
</tr>