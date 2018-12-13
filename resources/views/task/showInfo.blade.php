<h2>Informacje o zadaniu</h2>

<table>
  <tr>
    <th>nazwa zadania</th>
    <th>waga</th>
    <th>punkty</th>
    <th>nazwa arkusza<br />w excelu</th>
    <th>utworzono</th>
    <th>aktualizacja</th>
  </tr>
  <tr>
    <td>{{ $task->name }}</td>
    <td>{{ $task->importance }}</td>
    <td>{{ $task->points }}</td>
    <td>{{ $task->sheet_name }}</td>
    <td>{{ $task->created_at }}</td>
    <td>{{ $task->updated_at }}</td>
  </tr>
</table>