<select name="exam_type">
  <option value="0">- wybierz typ -</option>
  @foreach($examTypes as $examType)
    @if($examType == $examTypeSelected)
      <option selected="selected" value="{{$examType}}">{{$examType}}</option>
    @else
      <option value="{{$examType}}">{{$examType}}</option>
    @endif
  @endforeach
</select>