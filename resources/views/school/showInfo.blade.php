<h2>Informacje o szkole</h2>

<table>
  <tr>
    <th>identyfikator OKE</th>
    <td>{{ $school->id_OKE }}</td>
  </tr>
  <tr>
    <th>klasy</th>
    <td>{{ $school->grades->count() }}</td>
  </tr>
  <tr>
    <th>uczniowie</th>
    <td>{{ $school->students->count() }}</td>
  </tr>
</table>
