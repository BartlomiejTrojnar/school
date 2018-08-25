<select name="certificate_id">
  <option value="0">- wybierz świadectwo -</option>
  @foreach($certificates as $certificate)
    @if($certificate->id == $selectedCertificate)
      <option selected="selected" value="{{$certificate->id}}">
        {{$certificate->student->first_name}} {{$certificate->student->last_name}}
        {{$certificate->date_of_release}}
      </option>
    @else
      <option value="{{$certificate->id}}">
        {{$certificate->student->first_name}} {{$certificate->student->last_name}}
        {{$certificate->date_of_release}}
      </option>
    @endif
  @endforeach
</select>