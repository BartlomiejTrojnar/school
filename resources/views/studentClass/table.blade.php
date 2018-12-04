<h2>{{ $subTitle }}</h2>

<div id="gradeButtons" class="c">
<?php
  for($i=$grade->year_of_graduation-1; $i>=$grade->year_of_beginning; $i--)
    printf('<a class="btn btn-primary" data-year="%s">klasa %s</a>', $grade->year_of_beginning+($grade->year_of_graduation-$i), $grade->year_of_graduation-$i );
?>
</div>
<div class="form-inline c">
    <label for="date_start">data początkowa</label>
    <input id="date_start" type="date" class="form-control" name="date_start" placeholder="2018-08-31" size="12">
    <label for="date_end">data końcowa</label>
    <input id="date_end" type="date" class="form-control" name="date_end" placeholder="2018-08-31">
</div>

<table id="studentClasses">
  <tr>
    <th>uczeń</th>
    <th>klasa</th>
    <th>od</th>
    <th>do</th>
    <th>numer</th>
    <th>uwagi</th>
    <th colspan="2">+/-</th>
  </tr>

  @foreach($studentClasses as $sc)
    <tr data-start="{{ $sc->date_start }}" data-end="{{ $sc->date_end }}">
      <td><a href="{{ route('uczen.show', $sc->student_id) }}">
        {{ $sc->student->first_name }} {{ $sc->student->second_name }} {{ $sc->student->last_name }}
      </a></td>
      <td><a href="{{ route('klasa.show', $sc->grade_id) }}">
        {{ $sc->grade->year_of_beginning }} {{ $sc->grade->year_of_graduation }} {{ $sc->grade->symbol }}
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

  <tr class="create"><td colspan="8">
    <a href="{{ route('klasy_ucznia.create') }}">
      <img class="create btn" src="{{ asset('css/plus.png') }}" alt="--" />
    </a>
  </td></tr>
</table>
