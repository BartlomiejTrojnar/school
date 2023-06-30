<?php echo $groupSF; ?>
<button class="btn btn-primary" id="selectStudentsFromGroup">zaznacz grupę</button>
@if(!count($outsideGroupStudents))
   <p>Dla podanej daty nie znaleziono uczniów należących do klasy.</p>
@endif

<ul id="listOutsideGroupStudents">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.06.2023 *********************** -->
   @foreach($outsideGroupStudents as $outGS)
      <li data-student_id="{{ $outGS->student_id }}" data-grade_id="{{ $outGS->grade_id }}" data-grade_start="{{ $outGS->start }}" data-grade_end="{{ $outGS->end }}">
         <!--klasa ucznia-->
         <a href="{{ route('klasa.show', $outGS->grade_id) }}">
            {{ $year-$outGS->grade->year_of_beginning }}{{ $outGS->grade->symbol }}
         </a>

         <!--numer ucznia-->
         @foreach($outGS->student->numbers as $studentNumber)
            @if($outGS->grade_id==$studentNumber->grade_id && $studentNumber->school_year_id==$schoolYear)
               {{ $studentNumber->number }}
            @endif
         @endforeach

         <!--imię i nazwisko ucznia-->
         <a href="{{ route('uczen.show', $outGS->student_id) }}">{{ $outGS->student->first_name }} {{ $outGS->student->last_name }}</a>
      </li>
   @endforeach
</ul>