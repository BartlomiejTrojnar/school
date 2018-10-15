@extends('layouts.app')

@section('header')
  <h1>{{ $school->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('szkola.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('szkola.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('szkola.index') }}">powrót</a></li>
  </ul>

  <h2>klasy</h2>
  <table>
    <tr>
      <th>Lp</th>
      <th>rok rozpoczęcia</th>
      <th>rok ukończenia</th>
      <th>symbol</th>
      <th>+/-</th>
    </tr>
    @foreach($grades as $grade)
      <tr>
        <td>{{ $grade->id }}</td>
        <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</a></td>
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
    <tr class="create"><td colspan="5">
        <a href="{{ route('klasa.create') }}"><img class="destroy" src="{{ asset('css/plus.png') }}" /></a>
    </td></tr>
  </table>
@endsection