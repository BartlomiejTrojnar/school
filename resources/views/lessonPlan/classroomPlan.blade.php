<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.03.2022 ********************** -->
<section id="lessonPlanHeader" style="text-align: center;">
   <strong>plan lekcji dla daty:
      <input type="date" id="dateView" value="{{ $dateView }}" />
   </strong>
</section>

<table id="classroomPlan">
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
                  <li data-lesson_id="{{ $lesson->id }}">
                     <!-- data lekcji w planie -->
                     <span class="lessonDates"><span class="start">{{$lesson->start}}</span> - <span class="end">{{$lesson->end}}</span></span><br />
                     <!-- klasy -->
                     {{ $studyYear }}@foreach($lesson->group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
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
                     <span class="teacher">
                        @foreach($lesson->group->teachers as $groupTeacher)
                           @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
                              /{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}/
                           @endif
                        @endforeach
                     </span>
                  </li>
               @endif
            @endforeach
            </ul></td>
         @endfor
      </tr>
      @endfor
   </tbody>
</table>