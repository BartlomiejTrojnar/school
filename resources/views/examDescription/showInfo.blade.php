<h2>Informacje dotyczące opisu egzaminy</h2>

<table>
  <tr>
    <th>sesja</th>
    <td><a href="{{ route('sesja.show', $examDescription->session_id) }}">
      {{ $examDescription->session->year }} {{ $examDescription->session->type}}
    </a></td>
  </tr>
  <tr>
    <th>przedmiot</th>
    <td>{{ $examDescription->subject->name }}</td>
  </tr>
  <tr>
    <th>rodzaj</th>
    <td>{{ $examDescription->type }}</td>
  </tr>
  <tr>
    <th>poziom</th>
    <td>{{ $examDescription->level }}</td>
  </tr>
  <tr>
    <th>max punktów</th>
    <td>{{ $examDescription->max_points }}</td>
  </tr>
  <tr>
    <th>liczba egzaminów</th>
    <td>{{ count($examDescription->exams) }}</td>
  </tr>
  <tr>
    <th>liczba terminów</th>
    <td>{{ count($examDescription->terms) }}</td>
  </tr>
</table>