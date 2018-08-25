<select name="bookOfStudent_id">
  <option value="0">- wybierz numer księgi ucznia -</option>
  @foreach($bookOfStudents as $bookOfStudent)
    @if($bookOfStudent->id == $id)
      <option selected="selected" value="{{$bookOfStudent->id}}">
        {{$bookOfStudent->student->first_name}} {{$bookOfStudent->student->last_name}}
        ({{$bookOfStudent->school->name}})
      </option>
    @else
      <option value="{{$bookOfStudent->id}}">
        {{$bookOfStudent->student->first_name}} {{$bookOfStudent->student->last_name}}
        ({{$bookOfStudent->school->name}})
      </option>
    @endif
  @endforeach
</select>