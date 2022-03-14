<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 *********************** -->
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
         <input id="dateStart" type="date" name="dateStart" size="8" maxlength="10" value="{{ $lastRecord->date_start }}" />
         <input type="checkbox" name="confirmationDateStart" checked />
      </td>

      <!-- data końcowa przynależności ucznia do klasy -->
      <td class="c">
         <input type="date" name="dateEnd" size="8" maxlength="10" id="dateEnd" value="{{ $lastRecord->date_end }}" />
         <input type="checkbox" name="confirmationDateEnd" />
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
         <button class="btn btn-warning studentGradeDateStart">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button>
      @else
         <button class="btn btn-warning studentGradeDateStart">{{ date('Y-m-d') }}</button>
      @endIf
      <button class="btn btn-warning studentGradeDateStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
   </td>
   <td class="c proposedDates">
      @if( session()->get('dateSession') )
         <button class="btn btn-warning studentGradeDateEnd">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button>
      @else
         <button class="btn btn-warning studentGradeDateEnd">{{ date('Y-m-d', strtotime('-1 day')) }}</button>
      @endIf
      <button class="btn btn-warning studentGradeDateEnd">{{ $proposedDates['dateOfEndSchoolYear'] }}</button><br />
      <button class="btn btn-warning studentGradeDateEnd">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button>
      <button class="btn btn-warning studentGradeDateEnd">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button>
   </td>
</tr>