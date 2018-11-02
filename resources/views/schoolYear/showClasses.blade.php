@extends('layouts.app')

@section('header')
  <h1>rok szkolny {{ substr($schoolYear->date_start, 0, 4) }}/{{ substr($schoolYear->date_end, 0, 4) }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('rok_szkolny.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('rok_szkolny.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showTextbooks') }}">podręczniki</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('rok_szkolny.index') }}">powrót</a></li>
  </ul>

  <h2>Klasy</h2>
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

      <tr class="create">
        <td colspan="5"><a href="{{ route('klasa.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection
