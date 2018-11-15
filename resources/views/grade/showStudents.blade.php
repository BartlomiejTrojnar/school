@extends('layouts.app')

@section('header')
  <h1>{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('klasa.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('klasa.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents2') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showEnlargements') }}">rozszerzenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showDeclarationss') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTasks') }}">zadania</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">powrót</a></li>
  </ul>

  <h2>uczniowie w klasie</h2>

  <p><?php echo session()->get('dateSession') ?></p>

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
      @if($sc->confirmation_numer==1) <td class="c">{{ $sc->number }}</td>
      @else <td class="not_confirmation c">{{ $sc->number }}</td>
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

    <tr class="create"><td colspan="7">
      <a href="{{ route('klasy_ucznia.create', 'grade_id='.$grade->id) }}">
        <img class="create" src="{{ asset('css/plus.png') }}" /> dodaj ucznia
      </a>
    </td></tr>
  </table>
@endsection