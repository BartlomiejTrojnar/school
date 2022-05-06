<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.05.2022 *********************** -->
<tr id="studentGradeCreateRow">
   <form action="{{ route('klasy_ucznia.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <!-- uczeń (ukryty) i klasa -->
      <td colspan="2">
         {{$student->first_name}} {{$student->last_name}}
         <input type="hidden" name="student_id" value="{{$student->id}}" />
      </td>
      <td> <?php  echo $gradeSelectField;  ?> </td>

      <!-- data początkowa przynależności ucznia do klasy -->
      <td class="c">
         <input id="start" type="date" name="start" size="8" maxlength="10" value="{{ $lastRecord->start }}" />
         <input type="checkbox" name="confirmationStart" checked />
      </td>

      <!-- data końcowa przynależności ucznia do klasy -->
      <td class="c">
         <input type="date" name="end" size="8" maxlength="10" id="end" value="{{ $lastRecord->end }}" />
         <input type="checkbox" name="confirmationEnd" />
      </td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" rowspan="2">
         <button class="add btn btn-primary">dodaj</button>
         <button class="cancelAdd btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>

<!-- wiersz z propozycjami dat do wstawienia -->
<tr id="studentGradeProposedDates">
   <td colspan="3"></td>
   <td class="c proposedDates">
      @if( session()->get('dateSession') )
         <button class="btn btn-warning studentGradeStart">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button>
      @else
         <button class="btn btn-warning studentGradeStart">{{ date('Y-m-d') }}</button>
      @endIf
      <button class="btn btn-warning studentGradeStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
   </td>
   <td class="c proposedDates">
      @if( session()->get('dateSession') )
         <button class="btn btn-warning studentGradeEnd">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button>
      @else
         <button class="btn btn-warning studentGradeEnd">{{ date('Y-m-d', strtotime('-1 day')) }}</button>
      @endIf
      <button class="btn btn-warning studentGradeEnd">{{ $proposedDates['dateOfEndSchoolYear'] }}</button><br />
      <button class="btn btn-warning studentGradeEnd">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button>
      <button class="btn btn-warning studentGradeEnd">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button>
   </td>
</tr>