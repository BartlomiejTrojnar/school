<tr class="editRow" data-student_number_id="{{$studentNumber->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.12.2021 *********************** -->
   <form action="{{ route('numery_ucznia.update', $studentNumber->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <!-- uczeń, klasa i rok szkolny -->
      <td colspan="2">
         <input type="hidden" name="id" value="{{$studentNumber->id}}" />
         <?php  echo $studentSelectField;  ?>
         <input type="hidden" name="grade_id" value="{{$studentNumber->grade_id}}" />
      </td>
      <td> <?php  echo $schoolYearSelectField;  ?> </td>

      <!-- pole z numerem ucznia -->
      <td class="c">
         <input type="number" name="number" size="3" maxlength="2" value="{{ $studentNumber->number }}" id="number" />
         <input type="checkbox" name="confirmationNumber" @if($studentNumber->confirmation_number==1) checked="checked" @endif />
      </td>

      <!-- wiersz z przyciskami potwierdzenia zmian i anulowania -->
      <td class="c" style="width: 250px;">
         <button class="updateStudentNumber btn btn-primary" data-student_number_id="{{$studentNumber->id}}">zapisz zmiany</button>
         <button class="cancelUpdateStudentNumber btn btn-primary" data-student_number_id="{{$studentNumber->id}}">anuluj</button>
      </td>
   </form>
</tr>