<!--------------------- (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 --------------------->
<?php /*
<tr id="createRow">
   <form action="{{ route('klasy_ucznia.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <!-- uczeń i klasa (ukryta) -->
      <td colspan="2">
         {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
         <input type="hidden" name="grade_id" value="{{$grade->id}}" />
      </td>
      <td colspan="2"> <?php  echo $studentSelectField;  ?> </td>

      <!-- data początkowa przynależności ucznia do klasy -->
      <td class="c">
         <input type="date" name="dateStart" size="8" maxlength="10" value="{{ $lastRecord->date_start }}" />
         <input type="checkbox" name="confirmationDateStart" checked />
      </td>

      <!-- data końcowa przynależności ucznia do klasy -->
      <td class="c">
         <input type="date" name="dateEnd" size="8" maxlength="10" value="{{ $lastRecord->date_end }}" />
         <input type="checkbox" name="confirmationDateEnd" />
      </td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" rowspan="2">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>

<!-- wiersz z propozycjami dat do wstawienia -->
<tr id="proposedDates">
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
*/ ?>