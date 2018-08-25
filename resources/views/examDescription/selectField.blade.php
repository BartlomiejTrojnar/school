<select name="exam_description_id">
  <option value="0">- wybierz opis egzaminu -</option>
  @foreach($examDescriptions as $examDescription)
    @if($examDescription->id == $selectedExamDescription)
      <option selected="selected" value="{{$examDescription->id}}">
        {{$examDescription->session->year}}
        {{$examDescription->subject->name}}
        {{$examDescription->subject->type}}
        {{$examDescription->subject->level}}
      </option>
    @else
      <option value="{{$examDescription->id}}">
        {{$examDescription->session->year}}
        {{$examDescription->subject->name}}
        {{$examDescription->subject->type}}
        {{$examDescription->subject->level}}
      </option>
    @endif
  @endforeach
</select>