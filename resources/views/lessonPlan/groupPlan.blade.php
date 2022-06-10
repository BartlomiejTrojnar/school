<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.06.2022 ********************** -->
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

   <!-- Informacja o grupie -->
   <?php
      $studyYear = substr($dateView, 0, 4) - $group->grades[0]->grade->year_of_beginning;
      if( substr($dateView,5,2)>8 ) $studyYear++;
   ?>
   <div id="groupInfo" class="hidden">
      <!-- klasy -->
      {{ $studyYear }}@foreach($group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
      <!-- przedmiot i komentarz grupy -->
      {{ $group->subject->short_name }}
      <em style="font-size: 0.8em;">
         @if($group->level == 'rozszerzony') R @endif
         @if($group->level == 'podstawowy') P @endif
         @if($group->level == 'nieokreślony') @endif
         @if($group->comments) ({{$group->comments}}) @endif
      </em>
      <!-- liczba uczniów -->
      <code class="studentsCount"></code>
      <!-- nauczyciel -->
      <span class="teachers">
         @foreach($group->teachers as $groupTeacher)
            <span class="teacher" data-start="{{ $groupTeacher->start }}" data-end="{{ $groupTeacher->end }}">
               <time>{{ $groupTeacher->start }}</time>
               <time>{{ $groupTeacher->end }}</time>
               /{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}/
            </span>
         @endforeach
      </span>
   </div>

   <!-- uczniowie -->
   <ol id="groupStudents" class="hidden">
      @foreach($group->students as $groupStudent)
         <li data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}">   
            {{ $groupStudent->start }} {{ $groupStudent->end}} {{ $groupStudent->student_id }}
         </li>
      @endforeach
   </ol>
</section>

<section id="groupLessons">
   <ul>
      @foreach($groupLessons as $lesson)
         <li data-lesson_id="{{ $lesson->id }}" data-type="lesson" data-lesson_hour_id="{{ $lesson->lesson_hour_id }}">
            <!-- data lekcji w planie -->
            <span class="lessonDates" style="display: inline;"><time class="start">{{$lesson->start}}</time> - <time class="end">{{$lesson->end}}</time></span><br />
            <!-- informacja o grupie -->
            <div class="groupInfo" style="display: inline;"></div>
            <!-- sala -->
            <span class="classroom" style="display: inline;"><aside hidden>{{$lesson->classroom_id}}</aside>
               @if( !empty($lesson->classroom_id) ) {{ $lesson->classroom->name }} @endif
            </span>
         </li>
      @endforeach
   </ul>
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
      @for($i=1; $i<=10; $i++)
         <tr>
            <th>{{ $i }}</th>
            @for($dzien=0; $dzien<=60; $dzien=$dzien+15)
               <td class="lesson c" data-lesson_hour_id="{{$i+$dzien}}">
                  <ul></ul>
                  <button class="btn btn-primary" data-lesson_hour_id="{{$i+$dzien}}"><i class='fa fa-plus'></i></button>
               </td>
            @endfor
         </tr>
      @endfor
   </tbody>
</table>