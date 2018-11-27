<h2>uczniowie w szkole</h2>
<table>
  <tr>
    <th>Lp</th>
    <th>imiona</th>
    <th>nazwisko</th>
    <th>data urodzenia</th>
  </tr>

  @foreach($students as $student)
    <tr>
      <td>{{ $student->id }}</td>
      <td>{{ $student->first_name }} {{ $student->second_name }}</td>
      <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a> ({{ $student->family_name }})</td>
      <td>{{ $student->PESEL }}</td>
    </tr>
  @endforeach

</table>