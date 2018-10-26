@extends('layouts.app')
@section('css')
  <link href="{{ asset('css/taughtSubject.css') }}" rel="stylesheet">
@endsection

@section('java-script')
  <script src="{{ asset('js/taughtSubject.js') }}"></script>
@endsection

@section('header')
  <h1>{{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('nauczyciel.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('nauczyciel.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
    <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showInfo') }}">informacje</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showSubjects') }}">przedmioty</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showGroups') }}">grupy</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showLessonPlans') }}">plan lekcji</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('nauczyciel.index') }}">powrót</a></li>
    </ul>

    <h2>plan lekcji</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
