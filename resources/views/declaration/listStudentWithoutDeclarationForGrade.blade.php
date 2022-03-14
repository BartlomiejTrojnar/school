<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
<ol>
   @foreach($students as $student)
      <li class="col-lg-3">
         @if($student->countDeclarations)
            <input type="checkbox" name="student{{$student->id}}" value="{{$student->id}}">
         @else
            <input type="checkbox" name="student{{$student->id}}" value="{{$student->id}}" checked>
         @endif
         {{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}
      </li>
   @endforeach
</ol>