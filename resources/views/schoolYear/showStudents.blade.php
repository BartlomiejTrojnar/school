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


    <h2>Uczniowie</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
