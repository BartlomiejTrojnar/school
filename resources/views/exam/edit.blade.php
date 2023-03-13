<tr class="editRow" data-exam_id="{{$exam->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <form action="{{ route('egzamin.update', $exam->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <input type="hidden" name="lp" value="{{$lp}}" />
         <input type="hidden" name="id" value="{{$exam->id}}" />
         @if($version=="forDeclaration")
            <input type="hidden" name="declaration_id" value="{{$exam->declaration_id}}" />
            <?php  print_r($exam_description);  ?>
         @endif
         @if($version=="forExamDescription")
            <input type="hidden" name="exam_description_id" value="{{$exam->exam_description_id}}" />
            <?php  print_r($declaration);  ?>
         @endif
      </td>
      <td><?php  print_r($termSF);  ?></td>
      <td><?php  print_r($examTypeSF);  ?></td>
      <td><input type="text" name="points" size="5" maxlength="6" value="{{$exam->points}}" /></td>
      <td><input type="text" name="comments" size="10" maxlength="15" value="{{$exam->comments}}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="3" class="c">
         <button data-exam_id="{{$exam->id}}" class="update btn btn-primary" data-version="{{$version}}">zapisz zmiany</button>
         <button data-exam_id="{{$exam->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>