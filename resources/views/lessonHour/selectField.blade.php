<select name="lesson_hour_id">
  <option value="0">- wybierz godzinę -</option>
  @foreach($lessonHours as $lessonHour)
    @if($lessonHour->id == $selectedLessonHour)
      <option selected="selected" value="{{$lessonHour->id}}">{{$lessonHour->day}} {{$lessonHour->number}}</option>
    @else
      <option value="{{$lessonHour->id}}">{{$lessonHour->day}} {{$lessonHour->number}}</option>
    @endif
  @endforeach
</select>