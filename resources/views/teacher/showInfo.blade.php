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

    <h2>Informacje o nauczycielu</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>

    <p style="display: none;" id="teacher_id">{{ $teacher->id }}</p>
    <p>{{ $teacher->family_name }}</p>
    <p>{{ $teacher->short }}</p>
    <p>{{ $teacher->degree }}</p>
    <p>{{ $teacher->classroom_id }}</p>
    <p>od @if($teacher->first_year) {{ substr($teacher->first_year->date_start, 0, 4) }}/{{ substr($teacher->first_year->date_end, 0, 4)}} @endif
       do @if($teacher->last_year) {{ substr($teacher->last_year->date_start, 0, 4)}}/{{ substr($teacher->last_year->date_end, 0, 4) }} @endif</p>
    <p>{{ $teacher->order }}</p>
    <p>{{ $teacher->created_at }}</p>
    <p>{{ $teacher->updated_at }}</p>
    <p>grupy: {{ $teacher->groups->count() }}

@endsection
