@extends('layouts.app')

@section('header')
  <aside id="strzalka_l">
    <a href="{{ route('uczen.show', $previous) }}">
      <img src="{{ asset('css/strzalka_lewa.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('uczen.show', $next) }}">
      <img src="{{ asset('css/strzalka_prawa.png') }}" alt="nastepny">
    </a>
  </aside>
  <h1>{{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}</h1>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showTasks') }}">zadania</a></li>
<?php /*
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showEnlargements') }}">rozszerzenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showDeclarations') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showExams') }}">egzaminy</a></li>
*/ ?>
    <li class="nav-item"><a class="nav-link" href="{{ route('uczen.index') }}">powrót</a></li>
  </ul>

  <?php
    echo $subView;
  ?>
@endsection