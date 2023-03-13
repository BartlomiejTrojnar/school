<section id="studentsListForGroup">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 13.09.2022 *********************** -->
<div>sortowanie:
   <a href="{{ route('groupStudent.orderBy', 'grade_id') }}">klasa
      @if( session()->get('GroupStudentOrderBy[0]') == 'grade_id' )
         @if( session()->get('GroupStudentOrderBy[1]') == 'asc' )  <i class="fa fa-sort-alpha-asc"></i>  @else  <i class="fa fa-sort-alpha-desc"></i>  @endif
      @else  <i class="fa fa-sort"></i>  @endif
   </a>
   <a href="{{ route('groupStudent.orderBy', 'last_name') }}">nazwisko
      @if( session()->get('GroupStudentOrderBy[0]') == 'last_name' )
         @if( session()->get('GroupStudentOrderBy[1]') == 'asc' )  <i class="fa fa-sort-alpha-asc"></i>  @else  <i class="fa fa-sort-alpha-desc"></i>  @endif
      @else  <i class="fa fa-sort"></i>  @endif
   </a>
   <a href="{{ route('groupStudent.orderBy', 'first_name') }}">imię
      @if( session()->get('GroupStudentOrderBy[0]') == 'first_name' )
         @if( session()->get('GroupStudentOrderBy[1]') == 'asc' )  <i class="fa fa-sort-alpha-asc"></i>  @else  <i class="fa fa-sort-alpha-desc"></i>  @endif
      @else  <i class="fa fa-sort"></i>  @endif
   </a>
</div>

<ol>
   @foreach($groupStudents as $groupStudent)
      @foreach($groupStudent->student->grades as $studentGrade)
         <li data-group_student_id="{{$groupStudent->id}}" data-start="{{ $groupStudent->start }}" data-end="{{ $groupStudent->end }}" data-student_id="{{ $groupStudent->student_id }}"
            data-grade_id="{{ $studentGrade->grade_id }}" data-grade_start="{{ $studentGrade->start }}" data-grade_end="{{ $studentGrade->end }}" >
            <button class="edit"    data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-edit"></i></button>
            <button class="delete"  data-group_student_id="{{ $groupStudent->id }}"><i class="fa fa-remove"></i></button>

            <!-- klasa i numer ucznia -->
            <span class="grade">
               @if($studentGrade->start > $dateView || $studentGrade->end < $dateView) <i class='fas fa-exclamation-triangle'></i> @endif
               @if($dateView >= $studentGrade->grade->year_of_beginning."-09-01" && $dateView <= $studentGrade->grade->year_of_graduation."-08-31")
                  {{$year-$studentGrade->grade->year_of_beginning}}{{ $studentGrade->grade->symbol }}
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

            <!--imię i nazwisko-->
            <a href="{{ route('uczen.show', $groupStudent->student->id) }}">{{ $groupStudent->student->first_name }} {{ $groupStudent->student->last_name }}</a>
            <!--daty przynależności do grupy-->
            <span class="period">{{ $groupStudent->start }}-{{ $groupStudent->end }}</span>
         </li>
      @endforeach
   @endforeach <?php /* end groupStudents */ ?>
</ol>
</section>
<script src="http://code.jquery.com/color/jquery.color-2.1.2.js"></script>