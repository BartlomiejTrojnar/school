<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 03.12.2022 ********************** -->
<h2>Informacje o uczniu</h2>

<table>
   <tr>
      <th>Imiona i nazwisko</th>
      <td>{{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }} @if($student->family_name) ({{ $student->family_name }}) @endif</td>
   </tr>
   <tr>
      <th>PESEL</th>
      <td>{{ $student->PESEL }}</td>
   </tr>
   <tr>
      <th>miejsce urodzenia</th>
      <td>{{ $student->place_of_birth }}</td>
   </tr>
   <tr>
      <th>data urodzenia</th>
      @if(substr($student->PESEL, 2, 2) > "20")
         <td>20{{ substr($student->PESEL, 0, 2) }}-{{ substr($student->PESEL, 2, 2)-20 }}-{{ substr($student->PESEL, 4, 2) }}</td>
      @else
         <td>19{{ substr($student->PESEL, 0, 2) }}-{{ substr($student->PESEL, 2, 2) }}-{{ substr($student->PESEL, 4, 2) }}</td>
      @endif
   </tr>
   <tr>
      <th>aktualizacja</th>
      <td>{{ $student->updated_at }}</td>
   </tr>
   <tr>
      <th>aktualna klasa i numer</th>
      <td class="c">
         @foreach($student->grades as $grade)
            @if( $grade->date_start <= session()->get('dateSession') && $grade->date_end >= session()->get('dateSession') )
               <a href="{{ route('klasa.show', $grade->grade_id) }}">
                  @if($year)
                     {{ $year-$grade->grade->year_of_beginning }}{{ $grade->grade->symbol }}
                  @else
                     {{ $grade->grade->year_of_beginning }}-{{ $grade->grade->year_of_graduation }} {{ $grade->grade->symbol }}
                  @endif
               </a>  {{$grade->number}}
            @endif
         @endforeach
      </td>
   </tr>
   <tr>
      <th>księga uczniów <i class='fa fa-id-card'></i></th>
      <td id="bookOfStudent" class="c">
         @foreach($student->bookOfStudents as $bookOfSt)
            {{ $bookOfSt->number }}
         @endforeach
      </td>
   </tr>
</table>