<select name="lesson_type">
  <option value="0">- wybierz typ lekcji -</option>
  @foreach($lessonTypes as $type)
    @if($type == $lessonTypeSelected)
      <option selected="selected" value="{{$type}}">{{$type}}</option>
    @else
      <option value="{{$type}}">{{$type}}</option>
    @endif
  @endforeach
</select>