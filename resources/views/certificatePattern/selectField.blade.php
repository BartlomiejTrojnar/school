<select name="{{$name}}">
  <option value="0">- wybierz wzór świad. -</option>
  @foreach($certificatePatterns as $certificatePattern)
    @if($certificatePattern->id == $selectedCertificatePattern)
      <option selected="selected" value="{{$certificatePattern->id}}">{{$certificatePattern->name}}</option>
    @else
      <option value="{{$certificatePattern->id}}">{{$certificatePattern->name}}</option>
    @endif
  @endforeach
</select>