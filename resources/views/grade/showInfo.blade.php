<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
<h2>Informacje o klasie</h2>

<table>
  <tr>
    <th>szkoła</th>
    <td>{{$grade->school->name}}</td>
  </tr>
  <tr>
    <th>rok rozpoczęcia i ukończenia</th>
    <td class="c">{{$grade->year_of_beginning}}-{{$grade->year_of_graduation}}</td>
  </tr>
  <tr>
    <th>symbol</th>
    <td class="c">{{$grade->symbol}}</td>
  </tr>

  <tr>
    <th>liczba uczniów</th>
    <td>{{ count($grade->students) }}</td>
  </tr>
  <tr>
    <th>liczba grup</th>
    <td>{{ count($grade->groups) }}</td>
  </tr>
</table>