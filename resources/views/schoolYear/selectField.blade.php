<select name="{{ $name }}">
  <option value="0">- wybierz rok szkolny -</option>
  @foreach($schoolYears as $schoolYear)
    @if($schoolYear->id == $selectedSchoolYear)
      <option selected="selected" value="{{$schoolYear->id}}">{{$schoolYear->date_start}} {{$schoolYear->date_end}}</option>
    @else
      <option value="{{$schoolYear->id}}">{{$schoolYear->date_start}} {{$schoolYear->date_end}}</option>
    @endif
  @endforeach
</select>