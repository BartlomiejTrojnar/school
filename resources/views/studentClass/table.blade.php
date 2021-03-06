<h2>{{ $subTitle }}</h2>

@if( !empty($grade) )
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
@endif

<table id="studentClasses">
  <tr>
    <th>lp</th>
    <th><a href="{{ route('klasy_ucznia.order', 'student_id') }}">uczeń</a></th>
    <th><a href="{{ route('klasy_ucznia.order', 'grade_id') }}">klasa</a></th>
    <th><a href="{{ route('klasy_ucznia.order', 'date_start') }}">od</a></th>
    <th><a href="{{ route('klasy_ucznia.order', 'date_end') }}">do</a></th>
    <th><a href="{{ route('klasy_ucznia.order', 'number') }}">numer</a></th>
    <th><a href="{{ route('klasy_ucznia.order', 'comments') }}">uwagi</th>
    <th colspan="2">+/-</th>
  </tr>

  @foreach($studentClasses as $sc)
    @if($sc->student->sex == 'mężczyzna')
      <tr class="man" data-start="{{ $sc->date_start }}" data-end="{{ $sc->date_end }}" data-id="{{ $sc->id }}" data-number="{{ $sc->number }}">
    @else
      <tr class="woman" data-start="{{ $sc->date_start }}" data-end="{{ $sc->date_end }}" data-id="{{ $sc->id }}" data-number="{{ $sc->number }}">
    @endif
      <td>{{ $loop->iteration }}</td>
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

      @if($sc->confirmation_numer==1)
        <td>
      @else
        <td class="not_confirmation">
      @endif
          <span class="number">{{ $sc->number }}</span>
          <div class="upAndDown">
            <button class="btn btn-primary numberUp" data-id="{{ $sc->id }}"><img class="up" src="{{ asset('css/up.png') }}" alt="up" /></button>
            <button class="btn btn-primary numberDown" data-id="{{ $sc->id }}"><img class="down" src="{{ asset('css/down.png') }}" alt="down" /></button>
          </div>
        </td>

      @if($sc->confirmation_comments==1) <td>{{ $sc->comments }}</td>
      @else <td class="not_confirmation">{{ $sc->comments }}</td>
      @endif
      <td class="edit"><a class="btn btn-primary" href="{{ route('klasy_ucznia.edit', $sc->id) }}">
        <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="--">
      </a></td>
      <td class="destroy">
        <form action="{{ route('klasy_ucznia.destroy', $sc->id) }}" method="post" id="delete-form-{{$sc->id}}">
          {{ csrf_field() }}
          {{ method_field('DELETE') }}
            <button class="btn btn-primary"><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        </form>
      </td>
    </tr>
  @endforeach

  <tr class="create"><td colspan="9">
    @if( !empty($grade) )
      <a class="btn btn-primary" href="{{ route('klasy_ucznia.create', 'grade_id='.$grade->id) }}">
    @else
      <a class="btn btn-primary" href="{{ route('klasy_ucznia.create', 'student_id='.$student->id) }}">
    @endif
      <img class="create btn" src="{{ asset('css/plus.png') }}" alt="--" />
    </a>
  </td></tr>
</table>


@if( !empty($grade) )
  <p id="grade_id" class="hidden">{{ $grade->id }}</p>
  <p><a id="editAll" href="{{ route('klasy_ucznia.editAll') }}">edytuj wszystkie</a></p>
@endif
