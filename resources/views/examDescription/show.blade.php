@extends('layouts.app')

@section('header')
  <h1>{{ $examDescription->subject->name }} {{ $examDescription->session->year }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('opis_egzaminu.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('opis_egzaminu.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
    <p>{{ $student->family_name }}</p>
    <p>{{ $student->sex }}</p>
    <p>{{ $student->pesel }}</p>
    <p>{{ $student->place_of_birth }}</p>
    <p>{{ $student->created_at }}</p>
    <p>{{ $student->updated_at }}</p>

  <h2>klasy ucznia</h2>
  <table>
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
        {{ $sc->grade->year_of_beginning }}-{{ $sc->grade->year_of_graduation }}{{ $sc->grade->symbol }}
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

    <p><a href="{{ route('uczen.index') }}">powrót</a></p>
@endsection