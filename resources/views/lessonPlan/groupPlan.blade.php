<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 24.02.2022 ********************** -->
@section('css')
   <link href="{{ asset('public/css/lessonPlan.css') }}" rel="stylesheet">
   <link href="{{ asset('public/css/groupPlan.css') }}" rel="stylesheet">
@endsection


<section id="lessonPlanHeader">
   <strong>plan lekcji dla daty:
      <input type="date" id="dateView" value="{{ session()->get('dateView') }}" />
   </strong>
   <div id="completeRemove">upuść lekcję tutaj by całkiem usunąć</div>
   <div id="todayRemove">upuść lekcję tutaj by usunąć od dziś</div>
   <div id="groupHours">pozostało <span id="remainedHours">{{$group->hours}}</span>/<span id="allHours">{{$group->hours}}</span> lekcji</div>
</section>


<table id="groupPlan">
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
      <?php 
         $studyYear = substr($dateView,0,4) - $groupLessons[0]->group->grades[0]->grade->year_of_beginning;
         if( substr($dateView,5,2)>8 ) $studyYear++;
      ?>
      @for($i=1; $i<=10; $i++)
         <tr>
            <th>{{ $i }}</th>
            @for($dzien=0; $dzien<=60; $dzien=$dzien+15)
               <td class="lesson c" data-lessonHour_id="{{$i+$dzien}}"><ul>
                  @foreach($groupLessons as $lesson)
                     @if($lesson->lesson_hour_id == $i+$dzien)
                        <li data-lesson_id="{{ $lesson->id }}" data-type="lesson" data-group_id="{{ $lesson->group_id }}">
                           <!-- data lekcji w planie -->
                           <span class="lessonDates"><span class="start">{{$lesson->start}}</span>-<span class="end">{{$lesson->end}}</span></span><br />
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
                           <!-- sala -->
                           <span class="classroom"><aside hidden>{{$lesson->classroom_id}}</aside>
                              @if( !empty($lesson->classroom_id) ) {{ $lesson->classroom->name }} @endif
                           </span>
                        </li>
                     @endif
                  @endforeach
                  </ul>
                  <button class="btn btn-primary" data-lessonHour_id="{{$i+$dzien}}"><i class='fa fa-plus'></i></button>
               </td>
            @endfor
         </tr>
      @endfor
   </tbody>
</table>