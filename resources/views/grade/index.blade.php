{{ $grades->links() }}
<table id="grades">
  <thead>
    <tr>
      <th>id</th>
      <th><a href="{{ url('/klasa/sortuj/year_of_beginning') }}">klasa</a></th>
      <th><a href="{{ url('/klasa/sortuj/school_id') }}">szkoła</a></th>
      <th colspan="2">+/-</th>
    </tr>
  </thead>
  <tbody>

  @foreach($grades as $grade)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td><a href="{{ route('klasa.show', $grade->id.'/showStudents') }}">{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</a></td>
      <td><a href="{{ route('szkola.show', $grade->school_id) }}">{{ $grade->school->name }}</a></td>
      <td><a href="{{ route('klasa.edit', $grade->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--"></a></td>
      <td>
        <form action="{{ route('klasa.destroy', $grade->id) }}" method="post" id="delete-form-{{$grade->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
  @endforeach

    <tr class="create">
      <td colspan="5"><a href="{{ route('klasa.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
    </tr>
  </tbody>
</table>