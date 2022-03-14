<?php /*
<h4>Kopiowanie przynależności uczniów do klasy</h4>
<form action="{{ route('klasy_ucznia.storeFromPreviousYear') }}" method="post" role="form">
   {{ csrf_field() }}
   <table>
      <tr>
         <th>uczeń</th>
         <th>klasa</th>
         <th>data od</th>
         <th>data do</th>
         <th>uwagi</th>
      </tr>

      <tr>
         <th colspan="2">do wpisania</th>
         <td class="c">
            <input id="propositionDateStart" type="date" />
            <input id="confirmAllDateStart" type="checkbox" /><br />
            <button id="enterDateStart" class="btn btn-warning">wpisz</button>
         </td>
         <td class="c">
            <input id="propositionDateEnd" type="date" />
            <input id="confirmAllDateEnd" type="checkbox" /><br />
            <button id="enterDateEnd" class="btn btn-warning">wpisz</button>
         </td>
         <td class="c">
            <input id="propositionComments" type="text" size="25" />
            <input id="confirmAllComments" type="checkbox" /><br />
            <button id="enterComments" class="btn btn-warning">wpisz</button>
         </td>
      </tr>

      <?php $i=0; ?>
      @foreach($studentGrades as $studentGrade)
         <tr>
            <td>
               {{ $studentGrade->student->first_name }} {{ $studentGrade->student->last_name }}
               <input type="hidden" name="student_id{{++$i}}" value="{{ $studentGrade->student_id }}" />
            </td>
            <td>
               {{ $studentGrade->grade->year_of_beginning }}-{{ $studentGrade->grade->year_of_graduation }} {{ $studentGrade->grade->symbol }}
               <input type="hidden" name="grade_id{{$i}}" value="{{ $studentGrade->grade_id }}" />
            </td>
            <td>
               <input type="date" name="dateStart{{$i}}" value="{{ date( 'Y-m-d', strtotime($studentGrade->date_end.' +1 day') ) }}" size="10" />
               <input class="confirmationDateStart" type="checkbox" name="confirmationDateStart{{$i}}" />
            </td>
            <td>
               <input type="date" name="dateEnd{{$i}}" value="{{ date( 'Y-m-d', strtotime($studentGrade->date_end.' +1 year') ) }}" size="10" />
               <input class="confirmationDateEnd" type="checkbox" name="confirmationDateEnd{{$i}}" />
            </td>
            <td>
               <input type="text" name="comments{{$i}}" value="{{$studentGrade->comments}}" size="25" maxlength="32" />
               <input class="confirmationComments" type="checkbox" name="confirmationComments{{$i}}" />
            </td>
         </tr>
      @endforeach

      <tr class="submit"><td colspan="6">
            <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
            <button type="submit" class="btn btn-primary">dodaj uczniów</button>
            <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">anuluj</a>
      </tr>
   </table>
</form>
<script language="javascript" type="text/javascript" src="{{ asset('js/grade/studentGradeEditAll.js') }}"></script>
*/ ?>