@extends('layouts.app')

@section('header')
<?php /*
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
*/ ?>
@endsection

@section('main-content')
<p>Domek</p>
<?php /*
  <p><a href="{{ route('szkola.show', $grade->school_id) }}">{{ $grade->school->name }}</a></p>
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showLessonPlans') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showEnlargements') }}">rozszerzenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTasks') }}">zadania</a></li>
  </ul>
*/ ?>
  <?php
   // echo $nestView;
  ?>

@endsection