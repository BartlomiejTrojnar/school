<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 *********************** -->
<!-- otwarcie wiersza dla ucznia -->
@if($studentGrade->student->sex == 'mężczyzna')
   <tr class="man c" data-start="{{ $studentGrade->start }}" data-end="{{ $studentGrade->end }}" data-student_grade_id="{{ $studentGrade->id }}">
@else
   <tr class="woman c" data-start="{{ $studentGrade->start }}" data-end="{{ $studentGrade->end }}" data-student_grade_id="{{ $studentGrade->id }}">
@endif

   <td>{{$lp}}</td>
   <!-- numer z księgi ucznia -->
   <td>
      @foreach($studentGrade->student->bookOfStudents as $book) {{ $book->number }} @endforeach
      @if(count($studentGrade->student->bookOfStudents)==0) <a href="{{ route('ksiega_uczniow.create', "student_id=".$studentGrade->student->id) }}"><i class="fas fa-plus"></i></a> @endif
   </td>

   <!-- klasa ucznia -->
   <td>
      <a href="{{ route('klasa.show', $studentGrade->grade_id) }}">
         @if($year && $year<=$studentGrade->grade->year_of_graduation && $year>$studentGrade->grade->year_of_beginning)
            {{$year-$studentGrade->grade->year_of_beginning}}{{$studentGrade->grade->symbol}}
         @else
            {{$studentGrade->grade->year_of_beginning}}-{{$studentGrade->grade->year_of_graduation}} {{$studentGrade->grade->symbol}}
         @endif
      </a>
   </td>

   <!-- okres przynależności do klasy -->
   @if($studentGrade->confirmation_start==1) <td>{{ $studentGrade->start }}</td>
   @else <td class="not_confirmation">{{ $studentGrade->start }}</td>
   @endif
   @if($studentGrade->confirmation_end==1) <td>{{ $studentGrade->end }}</td>
   @else <td class="not_confirmation">{{ $studentGrade->end }}</td>
   @endif

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-student_grade_id="{{ $studentGrade->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-student_grade_id="{{ $studentGrade->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>