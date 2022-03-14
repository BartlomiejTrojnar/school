<select name="subject_id">
  <option value="0">- wybierz przedmiot -</option>
  @foreach($subjects as $subject)
    @if($subject->id == $subjectSelected)
      <option selected value="{{$subject->id}}">{{$subject->name}}</option>
    @else
      <option value="{{$subject->id}}">{{$subject->name}}</option>
    @endif
  @endforeach
</select>