<?php
   $year = substr($dateView,0,4);
   if( substr($dateView,5,2)>=8 )   $year = substr($dateView,0,4)+1;
?>
<ul id="listGroupStudentsInOtherTime">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 04.09.2022 *********************** -->
   @foreach($groupStudents as $groupStudent)
      @foreach($groupStudent->student->grades as $studentGrade)
         <?php
            $yearOfStudy = $year - $studentGrade->grade->year_of_beginning;
            $grade = $studentGrade->grade->symbol;
            $grade_id = $studentGrade->grade_id;
         ?>
      @endforeach

      @if($dateView < $groupStudent->start || $dateView > $groupStudent->end)
         <li data-group_student_id="{{$groupStudent->id}}" data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}" data-grade_id="{{$grade_id}}">
            <!--modyfikacja wpisu-->
            <button class="edit" data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-edit"></i></button>
            <button class="delete" data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-remove"></i></button>
            <!--klasa (ostatnia)-->
            {{$yearOfStudy}}{{$grade}}
            <!--dane ucznia-->
            <!--imię i nazwisko-->
            <a href="{{ route('uczen.show', $groupStudent->student_id) }}">{{ $groupStudent->first_name }} {{ $groupStudent->last_name }}</a>
            <!--daty przynależności do grupy-->
            <span class="period">{{ $groupStudent->start }}-{{ $groupStudent->end }}</span>
         </li>
      @else
         <li data-group_student_id="{{$groupStudent->id}}" data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}" data-grade_id="{{$grade_id}}">
            <!--modyfikacja wpisu-->
            <button class="edit" data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-edit"></i></button>
            <button class="delete" data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-remove"></i></button>
            <!--klasa (ostatnia)-->
            {{$yearOfStudy}}{{$grade}}
            <!--dane ucznia-->
            <!--imię i nazwisko-->
            <a href="{{ route('uczen.show', $groupStudent->student_id) }}">{{ $groupStudent->first_name }} {{ $groupStudent->last_name }}</a>
            <!--daty przynależności do grupy-->
            <span class="period">{{ $groupStudent->start }}-{{ $groupStudent->end }}</span>
         </li>
      @endif
   @endforeach <!-- koniec dla $groupStudent -->
</ul>