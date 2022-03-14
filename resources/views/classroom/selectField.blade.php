<select name="classroom_id">
  <option value="0">- wybierz salę -</option>
  @if( count($classrooms) )
    @foreach($classrooms as $classroom)
      @if($classroom->id == $classroomSelected)
        <option selected="selected" value="{{$classroom->id}}">{{$classroom->name}}</option>
      @else
        <option value="{{$classroom->id}}">{{$classroom->name}}</option>
      @endif
    @endforeach
  @endif
</select>