<tr data-group_id="{{ $group->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.03.2023 *********************** -->
   <td><a href="{{ route('grupa.show', $group->id) }}">{{ $lp }}</a></td>
   <td>
      <?php
         if(count($group->grades))  {
            $studyYear = substr($end, 0, 4) - $group->grades[0]->grade->year_of_beginning;
            if(substr($end, 5, 2) > 8) $studyYear++;
         }
      ?>
      @if(count($group->grades))
         {{$studyYear}}@foreach($group->grades as $grade)<a href="{{ route('klasa.show', $grade->grade_id) }}">{{ $grade->grade->symbol }}</a>@endforeach
      @endif
   </td>
   <td><a href="{{ route('przedmiot.show', $group->subject_id) }}">{{ $group->subject->name }}</a></td>
   <td>{{ $group->level }}</td>
   <td>{{ $group->comments }}</td>
   <td class="small">{{ $group->start }} - {{ $group->end }}</td>
   <td class="c">{{ $group->hours }}</td>
   <!-- nauczyciele -->
   <td class="small">
      @foreach($group->teachers as $groupTeacher)
         @if( $groupTeacher->start <= $end && $groupTeacher->end >= $start )
            {{ $groupTeacher->start }} {{ $groupTeacher->end }}
            <a href="{{ route('nauczyciel.show', $groupTeacher->teacher_id) }}">
               {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}
            </a><br />
         @endif
      @endforeach
   </td>
   <td class="c">
      <!-- liczba uczniów -->
      <?php
         $countGradeStudents=0; $countAllStudents=0;
         foreach($group->students as $groupStudent) {
            if($groupStudent->end >= $start && $groupStudent->start <= $end) {
               $countAllStudents++;
               foreach($groupStudent->student->grades as $studentGrade)
                  if($studentGrade->grade->id==$grade_id)  $countGradeStudents++;
            }
         }
      ?>
      @if($countGradeStudents != $countAllStudents) {{ $countGradeStudents }} ({{ $countAllStudents }})
      @else {{ $countGradeStudents }}
      @endif
   </td>
   <td class="c small">{{ substr($group->created_at, 0, 10) }}</td>
   <td class="c small">{{ substr($group->updated_at, 0, 10) }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit copy c">
      <a style="display: inline;" class="btn btn-primary" href="{{ route('grupa.copy', $group->id) }}"><i class="fa fa-copy"></i></a>
      <button class="edit btn btn-primary"    data-group_id="{{ $group->id }}" data-version="{{$version}}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-group_id="{{ $group->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>