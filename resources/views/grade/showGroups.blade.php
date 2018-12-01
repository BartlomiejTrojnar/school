<h2>Grupy w klasie</h2>
<table id="groups">
  <thead>
    <tr>
      <th>id</th>
      <th><a href="{{ url('/grupa/sortuj/subject_id') }}">przedmiot</a></th>
      <th><a href="{{ url('/grupa/sortuj/date_start') }}">data początkowa</a></th>
      <th><a href="{{ url('/grupa/sortuj/date_end') }}">data końcowa</a></th>
      <th>uwagi</th>
      <th><a href="{{ url('/grupa/sortuj/level') }}">poziom</a></th>
      <th>godziny</th>
      <th>wprowadzono</th>
      <th>aktualizacja</th>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
    @foreach($grade->groups as $groupClass)
      <tr>
        <td><a href="{{ route('grupa.show', $groupClass->group_id) }}">{{ $groupClass->group_id }}</a></td>
        <td><a href="{{ route('przedmiot.show', $groupClass->group->subject_id) }}"> {{ $groupClass->group->subject->name }}</a></td>
        <td>{{ $groupClass->group->date_start }}</td>
        <td>{{ $groupClass->group->date_end }}</td>
        <td>{{ $groupClass->group->comments }}</td>
        <td>{{ $groupClass->group->level }}</td>
        <td>{{ $groupClass->group->hours }}</td>
        <td>{{ $groupClass->group->created_at }}</td>
        <td>{{ $groupClass->group->updated_at }}</td>
        <td><a href="{{ route('grupa.edit', $groupClass->group_id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa.destroy', $groupClass->group_id) }}" method="post" id="delete-form-{{$groupClass->group_id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

    <tr class="create">
      <td colspan="11"><a href="{{ route('grupa.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
    </tr>
  </tbody>
</table>