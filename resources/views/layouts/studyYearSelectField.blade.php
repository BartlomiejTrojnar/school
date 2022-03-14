<select name="studyYear">
  <option value="0">- wybierz rok nauki -</option>
  @foreach($studyYears as $studyYear)
    @if($studyYear == $studyYearSelected)
      <option selected="selected" value="{{$studyYear}}">{{$studyYear}}</option>
    @else
      <option value="{{$studyYear}}">{{$studyYear}}</option>
    @endif
  @endforeach
</select>