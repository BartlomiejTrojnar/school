<li data-group_student_id="{{$groupStudent->id}}" data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}" >
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 19.02.2022 *********************** -->
   <a class="groupStudentEdit" href="{{ route('grupa_uczniowie.edit', $groupStudent->id) }}"><i class="fa fa-edit"></i></a>
   <a class="groupStudentDelete" href="{{ route('grupa_uczniowie.deleteForm', $groupStudent->id) }}"><i class="fa fa-remove"></i></a>
   <!-- klasa i numer ucznia -->
   @foreach($groupStudent->student->grades as $studentGrade)
      <span class="grade" href="{{ route('klasa.show', $studentGrade->grade_id) }}" data-start="{{ $studentGrade->start }}" data-end="{{ $studentGrade->end }}">
         @if($studentGrade->start > $dateView || $studentGrade->end < $dateView) <i class='fas fa-exclamation-triangle'></i> @endif
         @if($dateView >= $studentGrade->grade->year_of_beginning."-09-01" && $dateView <= $studentGrade->grade->year_of_graduation."-08-31")
            @if( substr($dateView,5,2)>=8 )
               {{substr($dateView,0,4)-$studentGrade->grade->year_of_beginning+1}}{{ $studentGrade->grade->symbol }}
            @else
               {{substr($dateView,0,4)-$studentGrade->grade->year_of_beginning}}{{ $studentGrade->grade->symbol }}
            @endif
         @else
            {{$studentGrade->grade->year_of_beginning}}-{{$studentGrade->grade->year_of_graduation}}{{ $studentGrade->grade->symbol }}
         @endif
         <!--numer ucznia-->
         @foreach($studentGrade->student->numbers as $studentNumber)
            @if($studentNumber->school_year_id==$schoolYear && $studentNumber->grade_id==$studentGrade->grade_id)
               {{$studentNumber->number}}
            @endif
         @endforeach
      </span>
   @endforeach
   <!--imię i nazwisko-->
   <a href="{{ route('uczen.show', $groupStudent->student->id) }}">{{ $groupStudent->student->first_name }} {{ $groupStudent->student->last_name }}</a>
   <!--daty przynależności do grupy-->
   <span style=" font-size: 0.7em; font-style: italic; color: #fff;">{{ $groupStudent->start }}-{{ $groupStudent->end }}</span>
</li>