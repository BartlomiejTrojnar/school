<select name="level">
  <option value="0">- wybierz poziom -</option>
  @foreach($levels as $level)
    @if($level == $levelSelected)
      <option selected="selected" value="{{$level}}">{{$level}}</option>
    @else
      <option value="{{$level}}">{{$level}}</option>
    @endif
  @endforeach
</select>