<tr class="createRow c">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 29.07.2021 ********************** -->
   <form action="{{ route('numery_ucznia.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <!-- uczeń, klasa i rok szkolny -->
      <td>
         <input type="hidden" name="student_id" value="{{$student_id}}" />
         <?php  echo $gradeSelectField;  ?>
      </td>
      <td> <?php  echo $schoolYearSelectField;  ?> </td>

      <!-- pole z numerem ucznia -->
      <td>
         <input type="number" name="number" size="3" maxlength="2" id="number" value="{{ $proposedNumber }}" />
         <input type="checkbox" name="confirmationNumber" />
         <button class="btn btn-primary studentGradeProposedNumber">{{ $proposedNumber }}</button>
      </td>

      <!-- wiersz z przyciskami dodawania i anulowania -->
      <td style="width: 200px;">
         <button class="add btn btn-primary">dodaj</button>
         <button class="cancelAdd btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>