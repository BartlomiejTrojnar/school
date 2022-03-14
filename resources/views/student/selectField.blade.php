<select name="student_id">
  <option value="0">- wybierz ucznia -</option>
  @foreach($students as $student)
    @if($student->id == $studentSelected)
      <option selected="selected" value="{{$student->id}}">{{$student->first_name}} {{$student->last_name}}</option>
    @else
      <option value="{{$student->id}}">{{$student->first_name}} {{$student->last_name}}</option>
    @endif
  @endforeach
</select>