<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 01.09.2021 *********************** -->
<tr class="editRow" data-student_grade_id="{{$studentGrade->id}}">
   <form action="{{ route('klasy_ucznia.update', $studentGrade->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <!-- id i klasa (ukryta) oraz uczeń -->
      <td colspan="2">
         <input type="hidden" name="id" value="{{$studentGrade->id}}" />
         {{$studentGrade->grade->year_of_beginning}}-{{$studentGrade->grade->year_of_graduation}} {{$studentGrade->grade->symbol}}
         <input type="hidden" name="grade_id" value="{{$studentGrade->grade_id}}" />
      </td>
      <td colspan="2"> <?php  echo $studentSelectField;  ?> </td>

      <!-- data początkowa przynależności ucznia do klasy -->
      <td>
         <input type="date" name="dateStart" size="8" maxlength="10" value="{{ $studentGrade->date_start }}" />
         <input type="checkbox" name="confirmationDateStart" @if($studentGrade->confirmation_date_start==1) checked="checked" @endif />
      </td>

      <!-- data końcowa przynależności ucznia do klasy -->
      <td>
         <input type="date" name="dateEnd" size="8" maxlength="10" value="{{ $studentGrade->date_end }}" />
         <input type="checkbox" name="confirmationDateEnd" @if($studentGrade->confirmation_date_end==1) checked="checked" @endif />
      </td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td rowspan="2" class="c">
         <button data-student_grade_id="{{$studentGrade->id}}" class="update btn btn-primary">zapisz zmiany</button>
         <button data-student_grade_id="{{$studentGrade->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
  </form>
</tr>

<!-- wiersz z propozycjami dat do wstawienia -->
<tr class="proposedDates" data-student_grade_id="{{$studentGrade->id}}">
   <td colspan="4"></td>
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