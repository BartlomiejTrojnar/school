<tr class="editRow" data-book_of_student_id="{{ $bookOfStudent->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 18.01.2023 *********************** -->
   <form action="{{ route('ksiega_uczniow.update', $bookOfStudent->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td><var class="lp">{{ $lp }}</var></td>
      <td><?php  print_r($schoolSF);  ?></td>
      <td><?php  print_r($studentSF);  ?></td>
      <td><input type="number" name="number" min="1" size="5" required value="{{ $bookOfStudent->number }}" /></td>
      <td colspan="4" class="c">
         <button class="update btn btn-primary"       data-book_of_student_id="{{ $bookOfStudent->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-book_of_student_id="{{ $bookOfStudent->id }}">anuluj</button>
      </td>
   </form>
</tr>