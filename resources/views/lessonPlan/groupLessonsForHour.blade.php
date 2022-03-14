@foreach($lessons as $lesson)
   <li data-lesson_id="{{ $lesson->id }}" data-type="lesson" data-group_id="{{ $lesson->group_id }}">
   <!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 24.02.2022 ********************** -->
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
@endforeach