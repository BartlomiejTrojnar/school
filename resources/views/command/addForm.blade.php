<tr>
  <th>nowe polecenie
    <input type="hidden" readonly name="task_id_{{$number}}" value="{{ $command->task_id }}" size="2" />
  </th>
  <td class="c"><input type="checkbox" name="add_{{$number}}" checked /></td>
  <td><input type="text" readonly name="number_{{$number}}" value="{{ $command->number }}" size="2" /></td>
  <td><input type="text" readonly name="command_{{$number}}" size="15" required value="{{ $command->command }}" /></td>
  <td><input type="text" readonly name="description_{{$number}}" size="20" value="{{ $command->description }}" /></td>
  <td><input type="text" readonly name="points_{{$number}}" size="3" value="{{ $command->points }}" /></td>
</tr>