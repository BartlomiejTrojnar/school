<h2>Deklaracje maturalne w klasie</h2>
<table id="declarations">
  <thead>
    <tr>
      <th>id</th>
      <th><a href="{{ url('/deklaracja/sortuj/student_id') }}">uczeń</a></th>
      <th><a href="{{ url('/deklaracja/sortuj/session_id') }}">sesja</a></th>
      <th><a href="{{ url('/deklaracja/sortuj/application_number') }}">numer zgłoszenia</a></th>
      <th><a href="{{ url('/deklaracja/sortuj/student_code') }}">kod ucznia</a></th>
      <th>wprowadzono</th>
      <th>aktualizacja</th>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
    @if($declarations != 0)
      @foreach($declarations as $declaration)
        <tr>
          <td><a href="{{ route('deklaracja.show', $declaration->id) }}">{{ $loop->iteration }}</a></td>
          <td><a href="{{ route('uczen.show', $declaration->student_id) }}">{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</a></td>
          <td><a href="{{ route('sesja.show', $declaration->session_id) }}">{{ $declaration->session->year }} {{ $declaration->session->type }}</a></td>
          <td>{{ $declaration->application_number }}</td>
          <td>{{ $declaration->student_code }}</td>
          <td>{{ $declaration->created_at }}</td>
          <td>{{ $declaration->updated_at }}</td>
          <td><a href="{{ route('deklaracja.edit', $declaration->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
          <td>
            <form action="{{ route('deklaracja.destroy', $declaration->id) }}" method="post" id="delete-form-{{$declaration->id}}">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
            </form>
          </td>
        </tr>
      @endforeach
    @endif

    <tr class="create">
      <td colspan="9"><a href="{{ route('deklaracja.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
    </tr>
  </tbody>
</table>
