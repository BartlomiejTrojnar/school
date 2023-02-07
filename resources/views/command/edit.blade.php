<tr class="editRow" data-command_id="{{ $command->id }}">>
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.02.2023 *********************** -->
   <form action="{{ route('polecenie.update', $command->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2"><input type="number" name="number" min="1" max="20" size="3" required value="{{ $command->number }}" /></td>
      <td><input type="text" name="command" size="15" maxlength="25" required value="{{ $command->command }}" /></td>
      <td><input type="text" name="description" size="40" maxlength="65" value="{{ $command->description }}" /></td>
      <td><input type="number" name="points" min="1" max="20" size="3" required value="{{ $command->points }}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="5" class="c">
         <input type="hidden" name="task_id" value="{{ $command->task_id }}" />
         <input type="hidden" name="id" value="{{ $command->id }}" />
         <input type="hidden" name="lp" value="{{ $lp }}" />
         <button class="update btn btn-primary"       data-command_id="{{$command->id}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-command_id="{{$command->id}}">anuluj</button>
      </td>
   </form>
</tr>