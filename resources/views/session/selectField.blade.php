<select name="session_id" @if(isset($autofocus)) autofocus @endif>
  <option value="0">- wybierz sesję -</option>
  @foreach($sessions as $session)
    @if($session->id == $sessionSelected)
      <option selected="selected" value="{{$session->id}}">{{$session->year}} {{$session->type}}</option>
    @else
      <option value="{{$session->id}}">{{$session->year}} {{$session->type}}</option>
    @endif
  @endforeach
</select>