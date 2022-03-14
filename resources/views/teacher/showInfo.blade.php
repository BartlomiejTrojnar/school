<h2>Informacje o nauczycielu</h2>
<table>
   <tr>
      <th>stopień</th>
      <td>{{ $teacher->degree }}</td>
   </tr>
   <tr>
      <th>skrót</th>
      <td>{{ $teacher->short }}</td>
   </tr>
   <tr>
      <th>imię</th>
      <td>{{ $teacher->first_name }}</td>
   </tr>
   <tr>
      <th>nazwisko</th>
      <td>{{ $teacher->last_name }} @if($teacher->family_name) ({{ $teacher->family_name }}) @endif</td>
   </tr>
   <tr>
      <th>przydzielona sala</th>
      <td>
         @if($teacher->classroom)
            {{ $teacher->classroom->name}}
         @endif
      </td>
   </tr>
   <tr>
      <th>lata pracy</th>
      <td>
         od @if($teacher->first_year) {{ substr($teacher->first_year->date_start, 0, 4) }}/{{ substr($teacher->first_year->date_end, 0, 4)}} @endif
         @if($teacher->last_year) do {{ substr($teacher->last_year->date_start, 0, 4)}}/{{ substr($teacher->last_year->date_end, 0, 4) }} @endif
      </td>
   </tr>
   <tr>
      <th>przedmioty</th>
      <td>
         <ul style="padding-left:2px; list-style: none;">
         @foreach($teacher->subjects as $ts)
            <li>{{ $ts->subject->name }}</li>
         @endforeach
         </ul>
      </td>
   </tr>
   <tr>
      <th>klasy/grupy</th>
      <td>{{ $teacher->groups->count() }}</td>
   </tr>
</table>