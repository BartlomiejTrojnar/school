<tr id="createRow" class="c">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.12.2021 *********************** -->
   <form action="{{ route('numery_ucznia.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <!-- uczeń, klasa i rok szkolny -->
      <td colspan="2">
         <input type="hidden" name="grade_id" value="{{$grade_id}}" />
         <?php  echo $studentSelectField;  ?>
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
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>