<tr>
  <th>stare dane</th>
  <td class="c"><input type="checkbox" name="discard_{{$number}}" /></td>
  <td>{{ $command->number }}</td>
  <td>{{ $command->command }}</td>
  <td>{{ $command->description }}</td>
  <td>{{ $command->points }}</td>
</tr>
<tr>
  <th>nowe dane</th>
  <td class="c">
    <input type="checkbox" name="edit_{{$number}}" checked />
    <input type="hidden" name="id_{{$number}}" value="{{ $newCommand->id }}" />
  </td>
  <td><input type="text" readonly name="number_{{$number}}" size="2" required value="{{ $newCommand->number }}" /></td>
  <td><input type="text" readonly name="command_{{$number}}" size="15" value="{{ $newCommand->command }}" /></td>
  <td><input type="text" readonly name="description_{{$number}}" size="40" value="{{ $newCommand->description }}" /></td>
  <td><input type="text" readonly name="points_{{$number}}" size="3" value="{{ $newCommand->points }}" /></td>
</tr>