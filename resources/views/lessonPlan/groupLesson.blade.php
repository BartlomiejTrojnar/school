<ul>
@foreach($lessons as $lesson)
  <li>
    [{{$lesson->date_start}} {{$lesson->date_end}}]
      @foreach($lesson->group->grades as $groupClass)
        {{$groupClass->grade->year_of_beginning}} {{$groupClass->grade->symbol}}
      @endforeach
    {{$lesson->group->subject->name}}
    {{$lesson->group->description}}
    ({{$lesson->group->students->count()}})
    /
      @foreach($lesson->group->teachers as $groupTeacher)
        {{$groupTeacher->teacher->first_name}} {{$groupTeacher->teacher->last_name}}
      @endforeach
    /
    {{$lesson->classroom_id}}
  </li>
@endforeach
</ul>