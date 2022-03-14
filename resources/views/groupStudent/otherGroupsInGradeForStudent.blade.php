<ul id="otherGroupsInStudentsClass">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 19.02.2021 *********************** -->
   @foreach($groups as $group)
      <li data-group="{{$group->id}}" class="{{$group->level}}">
         <button class="addGroup btn btn-primary" data-group="{{$group->id}}"><i class="fa fa-plus"></i></button>
         <span class="dates">{{$group->sstart}} <i class='fas fa-stopwatch' style='font-size: 1.2em;'></i> <time class="end">{{$group->end}}</time></span>
         <aside style="display: inline;">
            <span>
               {{$year - $group->grades[0]->grade->year_of_beginning}}@foreach($group->grades as $groupGrade){{$groupGrade->grade->symbol}}@endforeach
            </span>
            {{$group->subject->name}} {{$group->comments}}
            <small>{{$group->level}} {{$group->hours}}</small>
            @foreach($group->teachers as $groupTeacher)
               @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
                  <span class="small">{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}</span>
               @endif
            @endforeach
         </aside>
      </li>
   @endforeach
</ul>