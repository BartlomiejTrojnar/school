<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 25.02.2023 *********************** -->
<!-- otwarcie wiersza dla ucznia -->
<?php
   if($studentGrade->student->sex == 'mężczyzna')  $class = "man";
   else $class = "woman";
   $dateView = session()->get('dateView'); 
?>

<tr class="{{ $class }} c" data-start="{{ $studentGrade->start }}" data-end="{{ $studentGrade->end }}" data-student_grade_id="{{ $studentGrade->id }}">
   <td>{{ $lp }}</td>
   <!-- numer z księgi ucznia -->
   <td class="showCreateFormForBookOfStudent">
      @foreach($studentGrade->student->bookOfStudents as $book) {{ $book->number }} @endforeach
      @if(count($studentGrade->student->bookOfStudents)==0)
         <button class="btn btn-secondary"><i class="fa fa-plus"></i></button>
         <aside class="createForm"></aside>
      @endif
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
      <button class="edit btn btn-primary"            data-student_grade_id="{{ $studentGrade->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"         data-student_grade_id="{{ $studentGrade->id }}"><i class="fa fa-remove"></i></button>
      <button class="removeYesterday btn btn-primary" data-student_grade_id="{{ $studentGrade->id }}" title="usuń z klasy od wczoraj [{{date('Y-m-d', strtotime('-1 day', strtotime($dateView)))}}] (wraz z usunięciem z grup)"><i class="fa fa-user-times"></i></button>
   </td>
</tr>