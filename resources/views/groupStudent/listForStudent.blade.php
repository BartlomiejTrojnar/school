<ul id="studentGroups">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 10.02.2021 *********************** -->
   @foreach($studentGroups as $studentGroup)
      <li data-student_group="{{$studentGroup->id}}" class="{{$studentGroup->group->level}}">
         <button data-student_group="{{$studentGroup->id}}" class="edit btn btn-primary"><i class="fa fa-edit"></i></button>
         <button data-student_group="{{$studentGroup->id}}" class="destroy btn btn-primary"><i class="fa fa-remove"></i></button>
         <span class="dates"><span class="start">{{$studentGroup->start}}</span> <i class='fas fa-stopwatch' style='font-size: 1.2em;'></i> <span class="end">{{$studentGroup->end}}</span></span>
         <span>
            {{$year - $studentGroup->group->grades[0]->grade->year_of_beginning}}@foreach($studentGroup->group->grades as $groupGrade){{$groupGrade->grade->symbol}}@endforeach
         </span>
         {{$studentGroup->group->subject->name}} {{$studentGroup->group->comments}}
         <small>{{$studentGroup->group->level}} {{$studentGroup->group->hours}}</small>
         @foreach($studentGroup->group->teachers as $groupTeacher)
            @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
               <span class="teacher" data-start="{{$groupTeacher->start}}" data-end="{{$groupTeacher->end}}">{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}</span>
            @else
               <span class="teacher hide" data-start="{{$groupTeacher->start}}" data-end="{{$groupTeacher->end}}">{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}</span>
            @endif
         @endforeach
      </li>
   @endforeach
</ul>