<select name="task_id" class="form-control">
  <option value="0">- wybierz zadanie -</option>
  @foreach($tasks as $task)
    @if($task->id == $taskSelected)
      <option selected="selected" value="{{$task->id}}">{{$task->name}}</option>
    @else
      <option value="{{$task->id}}">{{$task->name}}</option>
    @endif
  @endforeach
</select>