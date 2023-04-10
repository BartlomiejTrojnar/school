<button class="btn btn-primary" id="selectStudentsFromGroup">zaznacz grupę</button>
<?php echo $groupSF; ?>

@if(!count($outsideGroupStudents))
   <p>Dla podanej daty nie znaleziono uczniów należących do klasy.</p>
@endif
<ul id="listOutsideGroupStudents">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.04.2023 *********************** -->
   @foreach($outsideGroupStudents as $outsideGroupStudent)
      @foreach($outsideGroupStudent->grades as $studentGrade)
      <?php /*
         @foreach($grades as $grade)   <!-- sprawdzanie czy klasa ucznia jest jedną z klas grupy -->
      */ ?>
            @if($grade==$studentGrade->grade_id)
               <li data-student_id="{{ $outsideGroupStudent->id }}" data-grade_id="{{ $studentGrade->grade_id }}" data-grade_start="{{ $studentGrade->start }}" data-grade_end="{{ $studentGrade->end }}">
                  <!--klasa ucznia-->
                  <a href="{{ route('klasa.show', $studentGrade->grade_id) }}">
                     {{ ($schoolYear+1900)-$studentGrade->grade->year_of_beginning }}{{ $studentGrade->grade->symbol }}
                  </a>

                  <!--numer ucznia-->
                  @foreach($studentGrade->student->numbers as $studentNumber)
                     @if($studentGrade->grade_id==$studentNumber->grade_id && $studentNumber->school_year_id==$schoolYear)
                        {{$studentNumber->number}}
                     @endif
                  @endforeach

                  <!--imię i nazwisko ucznia-->
                  <a href="{{ route('uczen.show', $outsideGroupStudent->id) }}">{{ $outsideGroupStudent->first_name }} {{ $outsideGroupStudent->last_name }}</a>
               </li>
            @endif
            <?php /*
         @endforeach <!-- sprawdzanie klasy -->
*/ ?>
      @endforeach
   @endforeach
</ul>