<ul id="studentGroups">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 03.07.2023 *********************** -->
   @foreach($studentGroups as $studentGroup)
      <li data-student_group="{{ $studentGroup->id }}" class="{{ $studentGroup->group->level }}">
         <button data-student_group="{{ $studentGroup->id }}" class="edit btn btn-primary"><i class="fa fa-edit"></i></button>
         <button data-student_group="{{ $studentGroup->id }}" class="destroy btn btn-primary"><i class="fa fa-remove"></i></button>
         &nbsp;&nbsp;
         <span class="dates"><span class="start">{{ $studentGroup->start }}</span> <i class="fa fa-clock-o" aria-hidden="true"></i> <span class="end">{{ $studentGroup->end }}</span></span>
         &nbsp;&nbsp;
         <span style="color: #090;">
            <?php 
               $grades = [];
               foreach( $studentGroup->group->grades as $sgg )   $grades[] = $sgg->grade_id;
               foreach( $studentGroup->student->grades as $sg )
                  if( in_array($sg->grade_id, $grades) )   $yearOfStudy = substr($studentGroup->end, 0, 4) - $sg->grade->year_of_beginning;
               $symbols = "";
               foreach($studentGroup->group->grades as $gg)    $symbols.= $gg->grade->symbol;
            ?>
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>{{ $yearOfStudy }}{{ $symbols }}
         </span>
         {{ $studentGroup->group->subject->name }} {{ $studentGroup->group->comments }}
         <small>{{ $studentGroup->group->level }}</small>
         @foreach($studentGroup->group->teachers as $groupTeacher)
            @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
               <span class="teacher" data-start="{{ $groupTeacher->start }}" data-end="{{ $groupTeacher->end }}">{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}</span>
            @else
               <span class="teacher hide" data-start="{{ $groupTeacher->start }}" data-end="{{ $groupTeacher->end }}">{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}</span>
            @endif
         @endforeach
      </li>
   @endforeach
</ul>