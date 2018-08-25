<select name="task_rating_id">
  <option value="0">- wybierz ocenę zadania -</option>
  @foreach($taskRatings as $taskRating)
    @if($taskRating->id == $selectedTaskRating)
      <option selected="selected" value="{{$taskRating->id}}">
        {{$taskRating->student->first_name}} {{$taskRating->student->last_name}}
        {{$taskRating->task->name}} {{$taskRating->task->version}}
      </option>
    @else
      <option value="{{$taskRating->id}}">
        {{$taskRating->student->first_name}} {{$taskRating->student->last_name}}
        {{$taskRating->task->name}} {{$taskRating->task->version}}
      </option>
    @endif
  @endforeach
</select>