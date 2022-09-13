@if(!count($outsideGroupStudents))
   <p>Dla podanej daty nie znaleziono uczniów należących do klasy.</p>
@endif

<ul id="listOutsideGroupStudents">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 13.09.2022 *********************** -->
   @foreach($outsideGroupStudents as $outsideGroupStudent)
      @foreach($outsideGroupStudent -> grades as $studentGrade)
         <li data-student_id="{{ $outsideGroupStudent->id }}" data-grade_id="{{ $studentGrade->grade_id }}" data-grade_start="{{ $studentGrade->start }}" data-grade_end="{{ $studentGrade->end }}">
            <!--klasa ucznia-->
            <a href="{{ route('klasa.show', $studentGrade->grade_id) }}">
               {{ $year-$studentGrade->grade->year_of_beginning }}{{ $studentGrade->grade->symbol }}
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
      @endforeach
   @endforeach
</ul>