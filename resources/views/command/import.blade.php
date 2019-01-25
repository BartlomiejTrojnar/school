<h2>importowanie zadania</h2>

<h3>polecenia</h3>

<table>
  <thead>
    <tr>
      <th>lp</th>
      <th>nazwa polecenia</th>
      <th>błąd</th>
      <th>przycisk</th>
    </tr>
  </thead>

  <tbody>
  @foreach($commandErrors as $commandError)
    <tr>
      <td>{{ $commandError['lp'] }}</td>
      <td>{{ $commandError['command'] }}</td>
      <td>{{ $commandError['error'] }}</td>
      <td>{{ $commandError['button'] }}</td>
    </tr>
  @endforeach
</table>