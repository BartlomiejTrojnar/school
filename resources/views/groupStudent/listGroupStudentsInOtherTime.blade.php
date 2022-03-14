<ul id="listGroupStudentsInOtherTime">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 19.02.2022 *********************** -->
   @foreach($groupStudents as $groupStudent)
      @foreach($groupStudent->student->grades as $studentGrade)
      <li data-group_student_id="{{$groupStudent->id}}" data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}"
         data-grade_id="{{ $studentGrade->grade_id }}" data-grade_start="{{ $studentGrade->start }}" data-grade_end="{{ $studentGrade->end }}">
         <!--modyfikacja wpisu-->
         <button class="edit" data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-edit"></i></button>
         <button class="delete" data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-remove"></i></button>

         <!-- klasa ucznia -->
         <?php $wypisane=false; ?>
            <!--jeżeli w podanej dacie uczeń należy do jakiejś klasy-->
            @if($studentGrade->start <= $dateView && $studentGrade->end >= $dateView)
               <?php $wypisane=true; ?>
               <a href="{{ route('klasa.show', $studentGrade->grade_id) }}">
                  @if($dateView >= $studentGrade->grade->year_of_beginning."-09-01" && $dateView <= $studentGrade->grade->year_of_graduation."-08-31")
                     @if( substr($dateView,5,2)>=8 )
                        {{substr($dateView,0,4)-$studentGrade->grade->year_of_beginning+1}}{{ $studentGrade->grade->symbol }}
                     @else
                        {{substr($dateView,0,4)-$studentGrade->grade->year_of_beginning}}{{ $studentGrade->grade->symbol }}
                     @endif
                  @else
                     {{$studentGrade->grade->year_of_beginning}}-{{$studentGrade->grade->year_of_graduation}}{{ $studentGrade->grade->symbol }}
                  @endif
               </a>
               <!--numer ucznia-->
               @foreach($studentGrade->student->numbers as $studentNumber)
                  @if($studentGrade->grade_id==$studentNumber->grade_id && $studentNumber->school_year_id==$schoolYear)
                     {{$studentNumber->number}}
                  @endif
               @endforeach
            @endif
   
         <!--jeżeli w podanej dacie uczeń nie należy do żadnej klasy-->
         @if(!$wypisane)
            <a href="{{ route('klasa.show', $studentGrade->grade_id) }}">
               {{$studentGrade->grade->year_of_beginning}}-{{$studentGrade->grade->year_of_graduation}}{{ $studentGrade->grade->symbol }}
            </a>
            @foreach($studentGrade->student->numbers as $studentNumber) @endforeach
            @if(isset($studentNumber)) {{$studentNumber->number}} @endif
         @endif

         <!--imię i nazwisko-->
         <a href="{{ route('uczen.show', $groupStudent->student_id) }}">{{ $groupStudent->first_name }} {{ $groupStudent->last_name }}</a>
         <!--daty przynależności do grupy-->
         <span class="period">{{ $groupStudent->start }}-{{ $groupStudent->end }}</span>
      </li>
      @endforeach <!-- koniec dla $studentGrade -->
   @endforeach <!-- koniec dla $groupStudent -->
</ul>