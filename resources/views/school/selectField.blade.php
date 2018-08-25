<select name="school_id">
  <option value="0">- wybierz szkołę -</option>
  @foreach($schools as $school)
    @if($school->id == $selectedSchool)
      <option selected="selected" value="{{$school->id}}">{{$school->name}}</option>
    @else
      <option value="{{$school->id}}">{{$school->name}}</option>
    @endif
  @endforeach
</select>