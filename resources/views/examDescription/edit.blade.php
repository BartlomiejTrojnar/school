<tr class="editRow" data-exam_description_id="{{$examDescription->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <form action="{{ route('opis_egzaminu.update', $examDescription->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <input type="hidden" name="id" value="{{$examDescription->id}}" />
         <?php  print_r($sessionSF);  ?>
      </td>
      <td><?php  print_r($subjectSF);  ?></td>
      <td><?php  print_r($examTypeSF);  ?></td>
      <td><?php  print_r($levelSF);  ?></td>
      <td><input type="number" name="max_points" size="3" min="1" max="100" value="{{$examDescription->max_points}}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="4" class="c">
         <button data-exam_description_id="{{$examDescription->id}}" class="update btn btn-primary">zapisz zmiany</button>
         <button data-exam_description_id="{{$examDescription->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>