<select name="grade_id">
  <option value="0">- wybierz klasę -</option>
  @foreach($grades as $grade)
    @if($grade->id == $selectedGrade)
      <option selected="selected" value="{{$grade->id}}">
        {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
      </option>
    @else
      <option value="{{$grade->id}}">
        {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
      </option>
    @endif
  @endforeach
</select>