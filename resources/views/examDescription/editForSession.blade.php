<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 26.09.2022 *********************** -->
<tr class="editRow" data-exam_description_id="{{$examDescription->id}}">
   <form action="{{ route('opis_egzaminu.update', $examDescription->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <input type="hidden" name="id" value="{{$examDescription->id}}" />
         <input type="hidden" name="session_id" value="{{$examDescription->session_id}}" />
         <?php  print_r($subjectSelectField);  ?>
      </td>
      <td><?php  print_r($examTypeSelectField);  ?></td>
      <td><?php  print_r($levelSelectField);  ?></td>
      <td><input type="number" name="max_points" min="1" max="100" value="{{$examDescription->max_points}}" size="4" /></td>
      <td colspan="2"></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="3" class="c">
         <button data-exam_description_id="{{$examDescription->id}}" class="update btn btn-primary">zapisz zmiany</button>
         <button data-exam_description_id="{{$examDescription->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>