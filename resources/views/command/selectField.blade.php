<select name="command_id">
  <option value="0">- wybierz polecenie -</option>
  @foreach($commands as $command)
    @if($command->id == $selectedCommand)
      <option selected="selected" value="{{$command->id}}">{{$command->command}}</option>
    @else
      <option value="{{$command->id}}">{{$command->command}}</option>
    @endif
  @endforeach
</select>