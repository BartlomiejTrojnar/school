<h2>{{ $subTitle }}</h2>
<table>
  <tr>
    <th>uczeń</th>
    <th>od</th>
    <th>do</th>
    <th>numer</th>
    <th>uwagi</th>
    <th colspan="2">+/-</th>
  </tr>

  @foreach($studentClasses as $sc)
    <tr>
      <td><a href="{{ route('uczen.show', $sc->student_id) }}">
        {{ $sc->student->first_name }} {{ $sc->student->second_name }} {{ $sc->student->last_name }}
      </a></td>
      @if($sc->confirmation_date_start==1) <td>{{ $sc->date_start }}</td>
      @else <td class="not_confirmation">{{ $sc->date_start }}</td>
      @endif
      @if($sc->confirmation_date_end==1) <td>{{ $sc->date_end }}</td>
      @else <td class="not_confirmation">{{ $sc->date_end }}</td>
      @endif
      @if($sc->confirmation_numer==1) <td>{{ $sc->numer }}</td>
      @else <td class="not_confirmation">{{ $sc->numer }}</td>
      @endif
      @if($sc->confirmation_comments==1) <td>{{ $sc->comments }}</td>
      @else <td class="not_confirmation">{{ $sc->comments }}</td>
      @endif
      <td><a href="{{ route('klasy_ucznia.edit', $sc->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--"></a></td>
      <td>
        <form action="{{ route('klasy_ucznia.destroy', $sc->id) }}" method="post" id="delete-form-{{$sc->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
          <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
  @endforeach
</table>
