<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 ********************** -->
<tr class="editRow c" data-student_grade_id="{{$studentGrade->id}}">
   <form action="{{ route('klasy_ucznia.update', $studentGrade->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <!-- id i uczeń (ukryte) oraz klasa -->
      <td>
         <input type="hidden" name="id" value="{{$studentGrade->id}}" />
         {{$studentGrade->student->first_name}} {{$studentGrade->student->last_name}}
         <input type="hidden" name="student_id" value="{{$studentGrade->student_id}}" />
         <input type="hidden" name="lp" value="{{$lp}}" />
      </td>
      <td colspan="2"> <?php  echo $gradeSelectField;  ?> </td>

      <!-- data początkowa przynależności ucznia do klasy -->
      <td>
         <input id="dateStart" type="date" name="dateStart" size="8" maxlength="10" value="{{ $studentGrade->date_start }}" />
         <input type="checkbox" name="confirmationDateStart" @if($studentGrade->confirmation_date_start==1) checked="checked" @endif />
      </td>

      <!-- data końcowa przynależności ucznia do klasy -->
      <td>
         <input id="dateEnd" type="date" name="dateEnd" size="8" maxlength="10" value="{{ $studentGrade->date_end }}" />
         <input type="checkbox" name="confirmationDateEnd" @if($studentGrade->confirmation_date_end==1) checked="checked" @endif />
      </td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="2" rowspan="2">
         <button data-student_grade_id="{{$studentGrade->id}}" class="update btn btn-primary">zapisz zmiany</button>
         <button data-student_grade_id="{{$studentGrade->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
  </form>
</tr>

<!-- wiersz z propozycjami dat do wstawienia -->
<tr class="c proposedDates" data-student_grade_id="{{$studentGrade->id}}">
   <td colspan="3"></td>
   <td>
      @if( session()->get('dateSession') )
         <button class="btn btn-warning studentGradeDateStart">{{ date('Y-m-d', strtotime('-1 day', strtotime(session()->get('dateSession')))) }}</button>
      @else
         <button class="btn btn-warning studentGradeDateStart">{{ date('Y-m-d') }}</button>
      @endIf
      <button class="btn btn-warning studentGradeDateStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
   </td>
   <td>
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