<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->
   <form action="{{ route('polecenie.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="number" name="number" min="1" max="20" size="3" required /></td>
      <td><input type="text" name="command" size="15" maxlength="25" required /></td>
      <td><input type="text" name="description" size="40" maxlength="65" /></td>
      <td><input type="number" name="points" min="1" max="20" size="3" required /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="5">
         <input type="hidden" name="task_id" value="{{$task}}" />
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>