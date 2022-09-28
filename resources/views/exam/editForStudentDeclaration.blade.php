<tr class="editRow" data-exam_id="{{$exam->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <form action="{{ route('egzamin.update', $exam->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td>
         <input type="hidden" name="id" value="{{$exam->id}}" />
         <input type="hidden" name="declaration_id" value="{{$exam->declaration_id}}" />
         <?php  print_r($exam_description);  ?>
      </td>
      <td><?php  print_r($examTypeSF);  ?></td>
      <td><?php  print_r($termSF);  ?></td>
      <td><input type="text" name="comments" size="10" maxlength="15" value="{{$exam->comments}}" /></td>
      <td><input type="text" name="points" size="5" maxlength="6" value="{{$exam->points}}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="2" class="c">
         <button data-exam_id="{{$exam->id}}" class="examUpdate btn btn-primary">zapisz zmiany</button>
         <button data-exam_id="{{$exam->id}}" class="examCancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>