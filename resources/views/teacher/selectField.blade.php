<select name="teacher_id" class="form-control">
  <option value="0">- wybierz nauczyciela -</option>
  @foreach($teachers as $teacher)
    @if($teacher->id == $teacherSelected)
      <option selected="selected" value="{{$teacher->id}}">{{$teacher->first_name}} {{$teacher->last_name}}</option>
    @else
      <option value="{{$teacher->id}}">{{$teacher->first_name}} {{$teacher->last_name}}</option>
    @endif
  @endforeach
</select>