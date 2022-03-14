<tr class="editRow" data-task_id="{{$task->id}}">>
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->
   <form action="{{ route('zadanie.update', $task->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2"><input type="text" name="name" value="{{$task->name}}" size="60" maxlength="150" required /></td>
      <td><input type="number" name="points" value="{{$task->points}}" min="1" max="1000" required /></td>
      <td><input type="text" name="importance" value="{{$task->importance}}" size="3" maxlength="4" required /></td>
      <td><input type="text" name="sheet_name" value="{{$task->sheet_name}}" size="12" maxlength="20" /></td>
      
      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="5" class="c">
         <input type="hidden" name="id" value="{{$task->id}}" />
         <button class="update btn btn-primary"       data-task_id="{{$task->id}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-task_id="{{$task->id}}">anuluj</button>
      </td>
   </form>
</tr>