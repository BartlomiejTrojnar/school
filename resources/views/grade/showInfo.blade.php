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
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showDeclarations') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTasks') }}">zadania</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">powrót</a></li>
  </ul>

    <h2>Informacje o klasie</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
