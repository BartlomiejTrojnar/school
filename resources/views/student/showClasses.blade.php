<h2>Klasy ucznia</h2>
<table id="studentGrades">
  <tr>
    <th>klasa</th>
    <th>od</th>
    <th>do</th>
    <th>numer</th>
    <th>uwagi</th>
    <th colspan="2">+/-</th>
  </tr>

  @foreach($studentClasses as $sc)
  <tr>
    <td><a href="{{ route('klasa.show', $sc->grade_id) }}">
      {{ $sc->grade->year_of_beginning }}-{{ $sc->grade->year_of_graduation }} {{ $sc->grade->symbol }}
      ({{ substr($sc->date_end, 0, 4) - $sc->grade->year_of_beginning }}{{ $sc->grade->symbol }})
    </a></td>
    @if($sc->confirmation_date_start==1) <td>{{ $sc->date_start }}</td>
    @else <td class="not_confirmation">{{ $sc->date_start }}</td>
    @endif
    @if($sc->confirmation_date_end==1) <td>{{ $sc->date_end }}</td>
    @else <td class="not_confirmation">{{ $sc->date_end }}</td>
    @endif
    @if($sc->confirmation_numer==1) <td class="c">{{ $sc->number }}</td>
    @else <td class="not_confirmation c">{{ $sc->number }}</td>
    @endif
    @if($sc->confirmation_comments==1) <td>{{ $sc->comments }}</td>
    @else <td class="not_confirmation">{{ $sc->comments }}</td>
    @endif
    <td><a href="{{ route('klasy_uczniow.edit', $sc->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--"></a></td>
    <td>
      <form action="{{ route('klasy_uczniow.destroy', $sc->id) }}" method="post" id="delete-form-{{$sc->id}}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
      </form>
    </td>
  </tr>
  @endforeach

  <tr class="create"><td colspan="7">
    <a href="{{ route('klasy_uczniow.create', 'student_id='.$student->id) }}">
      <img class="create" src="{{ asset('css/plus.png') }}" /> dodaj klasę
    </a>
  </td></tr>
</table>