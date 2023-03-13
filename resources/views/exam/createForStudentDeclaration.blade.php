<tr class="examCreateRow" data-declaration_id="{{$declarationID}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.09.2022 *********************** -->
   <form action="{{ route('egzamin.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td><?php  print_r($examDescriptionSF);  ?></td>
      <td><?php  print_r($examTypeSF);  ?></td>
      <td><?php  print_r($termSF);  ?></td>
      <td><input type="text" name="comments" size="10" maxlength="15" /></td>
      <td><input type="text" name="points" size="5" maxlength="6" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2">
         <input type="hidden" name="declaration_id" value="{{$declarationID}}" />
         <button class="btn btn-primary add">dodaj</button>
         <button class="btn btn-primary cancelAdd">anuluj</button>
      </td>
   </form>
</tr>