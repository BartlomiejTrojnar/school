<tr class="c editRow" data-student_number_id="{{$studentNumber->id}}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 03.12.2022 ********************** -->
   <form action="{{ route('numery_ucznia.update', $studentNumber->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <!-- uczeń, klasa i rok szkolny -->
      <td>
         <input type="hidden" name="id" value="{{$studentNumber->id}}" />
         <input type="hidden" name="student_id" value="{{$studentNumber->student_id}}" />
         <?php  echo $gradeSF;  ?>
      </td>
      <td> <?php  echo $schoolYearSF;  ?> </td>

      <!-- pole z numerem ucznia -->
      <td>
         <input type="number" name="number" size="3" maxlength="2" value="{{ $studentNumber->number }}" />
         <input type="checkbox" name="confirmationNumber" @if($studentNumber->confirmation_number==1) checked="checked" @endif />
      </td>

      <!-- wiersz z przyciskami potwierdzenia zmian i anulowania -->
      <td style="width: 250px;">
         <button class="update btn btn-primary" data-student_number_id="{{$studentNumber->id}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-student_number_id="{{$studentNumber->id}}">anuluj</button>
      </td>
   </form>
</tr>