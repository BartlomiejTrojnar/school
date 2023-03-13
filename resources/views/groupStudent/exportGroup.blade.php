<table>
  <tr>
    <td colspan="4" style="font-weight: bold;">{{$groupStudents[0]->group->subject->name}}</td>
    <td colspan="3">
      @foreach($groupStudents[0]->group->teachers as $groupTeacher)
        {{$groupTeacher->teacher->first_name}} {{$groupTeacher->teacher->last_name}}
      @endforeach
    </td>
  </tr>
<?php /*

  <tr>
    <td colspan="4" style="font-weight: bold;">
      @foreach($groupStudents[0]->group->grades as $groupGrade)
        @if($schoolYear)
          {{1900+$schoolYear-$groupGrade->grade->year_of_beginning}}{{$groupGrade->grade->symbol}}
        @else
          {{$schoolYear-$groupGrade->grade->year_of_beginning}}-{{$groupGrade->grade->year_of_graduation}}{{$groupGrade->grade->symbol}}
        @endif
      @endforeach
    </td>
    <td colspan="3" style="color: #ff1111; font-style: italic;">stan na: {{ $dateView }}</td>
  </tr>
</table>

<table>
  <tr>
    <th>lp</th>
    <th>klasa</th>
    <th>nr</th>
    <th>księga</th>
    <th>imiona</th>
    <th>nazwisko</th>
    <th>od</th>
    <th>do</th>
  </tr>

  <?php $lp=0; ?>
  @foreach($groupStudents as $groupStudent)
    <tr>
      <td>{{ ++$lp }}</td>
      <td>
        @foreach($groupStudent->student->grades as $studentGrade)
          @if($studentGrade->date_start <= $dateView && $studentGrade->date_end >= $dateView)
            @if($dateView >= $studentGrade->grade->year_of_beginning."-09-01" && $dateView <= $studentGrade->grade->year_of_graduation."-08-31")
              @if( substr($dateView,5,2)>=8 )
                {{substr($dateView,0,4)-$studentGrade->grade->year_of_beginning+1}}{{ $studentGrade->grade->symbol }}
              @else
                {{substr($dateView,0,4)-$studentGrade->grade->year_of_beginning}}{{ $studentGrade->grade->symbol }}
              @endif
            @else
              {{$studentGrade->grade->year_of_beginning}}-{{$studentGrade->grade->year_of_graduation}}{{ $studentGrade->grade->symbol }}
            @endif
      </td>
      <td style="text-align: center;">
            <!--numer ucznia-->
            @foreach($studentGrade->student->numbers as $studentNumber)
              @if($studentNumber->school_year_id==$schoolYear && $studentNumber->grade_id==$studentGrade->grade_id)
                {{$studentNumber->number}}
              @endif
            @endforeach
          @endif
        @endforeach
      </td>

      <!-- nr z księgi -->
      <td>{{ $groupStudent->student->bookOfStudents[0]->number }}</td>
      <!--imię i nazwisko-->
      <td>
        {{ $groupStudent->student->first_name }} {{ $groupStudent->student->second_name }}
      </td>
      <td>
        {{ $groupStudent->student->last_name }}
      </td>

      <!--daty przynależności do grupy-->
      <td>{{ $groupStudent->date_start }}</td>
      <td>{{ $groupStudent->date_end }}</td>
    </li>
  @endforeach
  */ ?>
</table>