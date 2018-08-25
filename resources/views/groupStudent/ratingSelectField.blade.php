<select name="{{ $name }}">
  <option value="0">- wybierz ocenę -</option>
  @foreach($ratings as $rating)
    @if($rating == $selectedRating)
      <option selected="selected" value="{{$rating}}">{{$rating}}</option>
    @else
      <option value="{{$rating}}">{{$rating}}</option>
    @endif
  @endforeach
</select>