<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 01.09.2021 *********************** -->
<h4>Modyfikacja przynależności uczniów do klasy</h4>
<form action="{{ route('klasy_ucznia.updateAll') }}" method="post" role="form">
   {{ csrf_field() }}
   <table>
      <tr>
         <th>uczeń</th>
         <th>klasa</th>
         <th>data od</th>
         <th>data do</th>
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
      </tr>

      @foreach($studentGrades as $sc)
         @if($sc->date_start <= $dateStart && $sc->date_end >= $dateEnd )
         <tr>
            <td>{{ $sc->student->first_name }} {{ $sc->student->last_name }}</td>
            <td>{{ $sc->grade->year_of_beginning }}-{{ $sc->grade->year_of_graduation }} {{ $sc->grade->symbol }}</td>
            <td>
               <input type="date" name="dateStart{{$sc->id}}" value="{{$sc->date_start}}" size="10" @if($sc->confirmation_date_start) readonly="readonly" @endif />
               <input class="confirmationDateStart" type="checkbox" name="confirmationDateStart{{$sc->id}}" @if($sc->confirmation_date_start) checked="checked" @endif />
            </td>
            <td>
               <input type="date" name="dateEnd{{$sc->id}}" value="{{$sc->date_end}}" size="10" @if($sc->confirmation_date_end) readonly="readonly" @endif />
               <input class="confirmationDateEnd" type="checkbox" name="confirmationDateEnd{{$sc->id}}" @if($sc->confirmation_date_end) checked="checked" @endif />
               @if($sc->student->sex == "mężczyzna")
                  <i style="color: red; font-size:20px;" class='fas fa-male'></i>
               @else
                  <i class='fas fa-female'></i>
               @endif
            </td>
         </tr>
         @endif
      @endforeach

      <tr class="submit"><td colspan="4">
            <input type="hidden" name="filterDateStart" value="{{$dateStart}}" />
            <input type="hidden" name="filterDateEnd" value="{{$dateEnd}}" />
            <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
            <button type="submit" class="btn btn-primary">zapisz zmiany</button>
            <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">anuluj</a>
      </tr>
   </table>
</form>
<script language="javascript" type="text/javascript" src="{{ asset('public/js/grade/studentGradeEditAll.js') }}"></script>