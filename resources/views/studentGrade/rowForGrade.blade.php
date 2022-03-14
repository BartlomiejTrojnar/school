<!--------------------- (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 --------------------->
<!-- otwarcie wiersza dla ucznia -->
<?php /*
@if($studentGrade->student->sex == 'mężczyzna')
   <tr class="man" data-start="{{ $studentGrade->date_start }}" data-end="{{ $studentGrade->date_end }}" data-studentGradeId="{{ $studentGrade->id }}">
@else
   <tr class="woman" data-start="{{ $studentGrade->date_start }}" data-end="{{ $studentGrade->date_end }}" data-studentGradeId="{{ $studentGrade->id }}">
@endif

   <td>???</td>
   <!-- numer z księgi ucznia -->
   <td>
      @foreach($studentGrade->student->bookOfStudents as $book) {{ $book->number }} @endforeach
      @if(count($studentGrade->student->bookOfStudents)==0) <a href="{{ route('ksiega_uczniow.create', "student_id=".$studentGrade->student->id) }}"><i class="fas fa-plus"></i></a> @endif
   </td>
   <!-- imię i nazwisko -->
   <td><a href="{{ route('uczen.show', $studentGrade->student->id) }}">{{ $studentGrade->student->first_name }} {{ $studentGrade->student->second_name }} {{ $studentGrade->student->last_name }}</a></td>

   <!-- klasa ucznia -->
   <td class="c">
      @foreach( $studentGrade->student->numbers as $studentNumber )
         @if( !empty($grade) && $studentNumber->school_year_id == $schoolYearEnd && $studentNumber->grade_id == $grade->id)
            {{$studentNumber->number}}
         @endif
      @endforeach
   </td>

   <!-- okres przynależności do klasy -->
   @if($studentGrade->confirmation_date_start==1) <td>{{ $studentGrade->date_start }}</td>
   @else <td class="not_confirmation">{{ $studentGrade->date_start }}</td>
   @endif
   @if($studentGrade->confirmation_date_end==1) <td>{{ $studentGrade->date_end }}</td>
   @else <td class="not_confirmation">{{ $studentGrade->date_end }}</td>
   @endif

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary" data-studentGradeId="{{ $studentGrade->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-studentGradeId="{{ $studentGrade->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>
*/ ?>