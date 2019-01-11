<h2>{{ $subTitle }}</h2>

<table id="commands">
  <thead>
    <tr>
      <th>id</th>
      <th>numer</th>
      <th>polecenie</th>
      <th>opis</th>
      <th>punkty</th>
      <th>wprowadzono</th>
      <th>aktualizacja</th>
    </tr>
  </thead>

  <tbody>
  @if( !empty($commands) )
  @foreach($commands as $command)
    <tr>
      <td>{{ $command->id }}</td>
      <td>{{ $command->number }}</td>
      <td>{{ $command->command }}</td>
      <td>{{ $command->description }}</td>
      <td>{{ $command->points }}</td>
      <td>{{ $command->created_at }}</td>
      <td>{{ $command->updated_at }}</td>
    </tr>
  @endforeach
  @endif
</table>