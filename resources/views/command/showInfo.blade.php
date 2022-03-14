<h2>Informacje o poleceniu</h2>

<table>
  <tr>
    <th>zadanie</th><th>numer</th><th>opis</th><th>punkty</th>
    <th>liczba ocen</th>
    <th>utworzono</th><th>zaktualizowano</th>
  </tr>
  <tr>
    <td><a href="{{ route('zadanie.show', $command->task_id) }}">{{ $command->task->name }}</a></td>
    <td>{{ $command->number }}</td>
    <td>{{ $command->description }}</td>
    <td>{{ $command->points }}</td>
    <?php /*<td>{{ $command->commandRatings->count() }}</td>*/ ?>
    <td>liczba ocen (do zrobienia)</td>
    <td>{{ $command->created_at }}</td>
    <td>{{ $command->updated_at }}</td>
  </tr>
</table>