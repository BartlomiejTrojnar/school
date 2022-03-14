<ol>
  @foreach($students as $student)
    <li class="col-lg-3">
      <input type="checkbox" name="student{{$student->id}}" value="{{$student->id}}" checked>
      {{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}
    </li>
  @endforeach
</ol>