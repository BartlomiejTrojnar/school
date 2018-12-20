<h2>{{ $subTitle }}</h2>

<table id="taskRatings">
  <tr>
    <th>uczeń</th>
    <th>termin</th>
    <th>data realizacji</th>
    <th>wersja</th>
    <th>waga</th>
    <th>data oceny</th>
    <th>punkty</th>
    <th>ocena</th>
    <th>uwagi</th>
    <th>dziennik?</th>
    <th>data dziennika</th>
    <th colspan="2">+/-</th>
  </tr>

  @foreach($taskRatings as $taskRating)
    <tr>
      <td><a href="{{ route('uczen.show', $taskRating->student_id) }}">
          {{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}
      </a></td>
      <td>{{ $taskRating->deadline }}</td>
      <td>{{ $taskRating->implementation_date }}</td>
      <td>{{ $taskRating->version }}</td>
      <td>{{ $taskRating->importance }}</td>
      <td>{{ $taskRating->rating_date }}</td>
      <td>{{ $taskRating->points }}</td>
      <td>{{ $taskRating->rating }}</td>
      <td>{{ $taskRating->comments }}</td>
      <td>{{ $taskRating->diary }}</td>
      <td>{{ $taskRating->diary_date }}</td>

      <td class="edit"><a class="btn btn-primary" href="{{ route('ocena_zadania.edit', $taskRating->id) }}">
          <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--">
      </a></td>
      <td class="destroy">
        <form action="{{ route('ocena_zadania.destroy', $taskRating->id) }}" method="post" id="delete-form-{{$taskRating->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button class="btn btn-primary"><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
  @endforeach

  <tr class="create"><td colspan="13">
      <a class="btn btn-primary" href="{{ route('ocena_zadania.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a>
  </td></tr>
</table>
