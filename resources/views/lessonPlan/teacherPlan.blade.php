<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.03.2022 ********************** -->
@section('css')
   <link href="{{ asset('public/css/lessonPlan.css') }}" rel="stylesheet">
   <link href="{{ asset('public/css/lessonPlanPrint.css') }}" rel="stylesheet" media="print">
@endsection


<?php  $dateView = session()->get('dateView');  ?>
<h2 id="dateForView">{{$dateView}}</h2>
<section id="lessonPlanHeader" style="text-align: center;">
   <strong>plan lekcji dla daty:
      <input type="date" id="dateView" value="{{ $dateView }}" />
   </strong>
   <div id="completeRemove">upuść lekcję tutaj by całkiem usunąć</div>
</section>

<section id="teacherGroups"><ul>
   @foreach($groups as $group)
      <li class="teacherGroup" data-group_id="{{$group->id}}" data-type="group" data-hours="{{$group->hours}}">
         <span class="groupDates"><span class="start">{{$group->start}}</span> : <span class="end">{{$group->end }}</span></span><br />
         {{ $year - $group->grades[0]->grade->year_of_beginning }}@foreach($group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
         {{ $group->subject->name }}<br />
         <em style="font-size: 0.8em;">{{ $group->level }} ({{$group->comments}})</em>
         {<span class="hours">{{ $group->hours }}</span>}
         <!-- liczba uczniów -->
         <?php
            $countGradeStudents=0; $countAllStudents=0;
            foreach($group->students as $groupStudent)
               if($groupStudent->end >= $dateView && $groupStudent->start <= $dateView)   $countAllStudents++;
         ?>
         <code title="liczba uczniów" class="countStudents">[{{ $countAllStudents }}]</code><br />
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
            @for($dzien=0; $dzien<=60; $dzien=$dzien+15)
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
                           <?php
                              $countAllStudents=0;
                              foreach($lesson->group->students as $groupStudent)
                                 if($groupStudent->end >= $dateView && $groupStudent->start <= $dateView)   $countAllStudents++;
                           ?>
                           <code title="liczba uczniów">[{{ $countAllStudents }}]</code>
                           <!-- nauczyciel -->
                           <span class="teacher" hidden>
                              @foreach($lesson->group->teachers as $groupTeacher)
                                 @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
                                    <aside>{{$groupTeacher->teacher_id}}</aside>
                                    /{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}/
                                 @endif
                              @endforeach
                           </span>
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