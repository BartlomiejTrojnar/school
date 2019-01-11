<h2>{{ $subTitle }}</h2>

<table id="commands">
  <thead>
    <tr>
      <th>id</th>
      <th><a href="{{ url('/polecenie/sortuj/task_id') }}">zadanie</a></th>
      <th><a href="{{ url('/polecenie/sortuj/number') }}">numer</a></th>
      <th><a href="{{ url('/polecenie/sortuj/command') }}">polecenie</a></th>
      <th><a href="{{ url('/polecenie/sortuj/description') }}">opis</a></th>
      <th><a href="{{ url('/polecenie/sortuj/points') }}">punkty</a></th>
      <th>wprowadzono</th>
      <th>aktualizacja</th>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
  @if( !empty($commands) )
  @foreach($commands as $command)
    <tr>
      <td>{{ $command->id }}</td>
      <td><a href="{{ route('zadanie.show', $command->task_id) }}">{{ $command->task->name }}</a></td>
      <td>{{ $command->number }}</td>
      <td><a href="{{ route('polecenie.show', $command->id) }}">{{ $command->command }}</a></td>
      <td>{{ $command->description }}</td>
      <td>{{ $command->points }}</td>
      <td>{{ $command->created_at }}</td>
      <td>{{ $command->updated_at }}</td>

      <td class="edit"><a class="btn btn-primary" href="{{ route('polecenie.edit', $command->id) }}">
          <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]">
      </a></td>
      <td class="destroy">
        <form action="{{ route('polecenie.destroy', $command->id) }}" method="post" id="delete-form-{{$command->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button class="btn btn-primary"><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
  @endforeach
  @endif

    <tr class="create"><td colspan="10">
        <a class="btn btn-primary" href="{{ route('polecenie.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a>
    </td></tr>
  </tbody>
</table>

<a class="btn btn-primary" href="{{ route('polecenie.export', $command->task_id) }}">eksportuj</a>
&lt; plik Excel &gt;
<a class="btn btn-primary" href="{{ route('polecenie.import', $command->task_id) }}">importuj</a>