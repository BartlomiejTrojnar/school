<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.03.2022 ********************** -->
@section('css')
   <link href="{{ asset('public/css/lessonPlan.css') }}" rel="stylesheet">
   <link href="{{ asset('public/css/lessonPlanPrint.css') }}" rel="stylesheet" media="print">
@endsection


<section id="lessonPlanHeader" style="text-align: center;">
   <strong>plan lekcji dla daty:
      <input type="date" id="dateView" value="{{ $dateView }}" />
   </strong>
   <div id="completeRemove">upuść lekcję tutaj by całkiem usunąć</div>
   <ul id="schoolYearEnds" class="hidden">
      @foreach($schoolYearEnds as $syEnd)
         <li>{{$syEnd->date_of_graduation}}</li>
      @endforeach
   </ul>
</section>

<section id="teacherGroups"><ul>
   @foreach($groups as $group)
      <li class="teacherGroup" data-group_id="{{$group->id}}" data-type="group" data-hours="{{$group->hours}}">
         <span class="groupDates"><time class="start">{{$group->start}}</time> : <time class="end">{{$group->end }}</time></span><br />
         {{ $year - $group->grades[0]->grade->year_of_beginning }}@foreach($group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
         {{ $group->subject->name }}<br />
         <em style="font-size: 0.8em;">
            @if($group->level == 'rozszerzony') R @endif
            @if($group->level == 'podstawowy') P @endif
            @if($group->level == 'nieokreślony') <span style="background: #333;">nieokr</span> @endif
            @if($group->comments) ({{$group->comments}}) @endif
         </em>
         <span class="hours">{<var>{{ $group->hours }}</var>}</span>
         <!-- liczba uczniów -->
         <code class="studentsCount"></code>
         <!-- uczniowie -->
         <ol class="groupStudents hidden">
            @foreach($group->students as $groupStudent)
               <li data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}">   
                  {{ $groupStudent->start }} {{ $groupStudent->end}} {{ $groupStudent->student_id }}
               </li>
            @endforeach
         </ol>
      </li>
   @endforeach
</ul></section>

<table id="teacherPlan">
   <thead>
      <tr>
         <th>godziny</th>
         <th>poniedziałek</th>
         <th>wtorek</th>
         <th>środa</th>
         <th>czwartek</th>
         <th>piątek</th>
      </tr>
   </thead>

   <tbody>
   @for($i=1; $i<=10; $i++)
         <tr>
            <th>{{ $i }}</th>
            @for($dzien=0; $dzien<=60; $dzien = $dzien+15)
               <td class="lesson c" data-lessonHour_id="{{$i+$dzien}}"><ul>
                  @foreach($lessons as $lesson)
                     @if($lesson->lesson_hour_id == $i+$dzien)
                        <li data-lesson_id="{{ $lesson->id }}" data-type="lesson" data-group_id="{{ $lesson->group_id }}">
                           <!-- data lekcji w planie -->
                           <span class="lessonDates"><span class="start">{{$lesson->start}}</span>-<span class="end">{{$lesson->end}}</span></span><br />
                           <!-- klasy -->
                           {{ $year - $lesson->group->grades[0]->grade->year_of_beginning }}@foreach($lesson->group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
                           <!-- przedmiot i komentarz grupy -->
                           {{ $lesson->group->subject->short_name }}
                           <em style="font-size: 0.8em;">
                              @if($lesson->group->level == 'rozszerzony') R @endif
                              @if($lesson->group->level == 'podstawowy') P @endif
                              @if($lesson->group->level == 'nieokreślony') @endif
                              @if($lesson->group->comments) ({{$lesson->group->comments}}) @endif
                           </em>
                           <!-- liczba uczniów -->
                           <code class="studentsCount" title="liczba uczniów"></code>
                           <!-- nauczyciel -->
                           @foreach($lesson->group->teachers as $groupTeacher)
                           <span class="teacher" hidden data-start="{{$groupTeacher->start}}" data-end="{{$groupTeacher->end}}">
                              /{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}/
                           </span>
                           @endforeach
                           <!-- sala -->
                           <span class="classroom"><aside hidden>{{$lesson->classroom_id}}</aside>
                              @if( !empty($lesson->classroom_id) ) {{ $lesson->classroom->name }} @endif
                           </span>
                        </li>
                     @endif
                  @endforeach
                  </ul>
               </td>
            @endfor
         </tr>
      @endfor

   </tbody>
</table>