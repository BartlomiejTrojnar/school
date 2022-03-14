<ol>
  @foreach($students as $student)
    <li data-student_id="{{ $student->id }}">
      {{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}
    </li>
  @endforeach
</ol>