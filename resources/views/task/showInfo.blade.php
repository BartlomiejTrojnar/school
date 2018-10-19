@extends('layouts.app')

@section('header')
  <h1>{{ $task->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('zadanie.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('zadanie.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showCommands') }}">polecenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">powrót</a></li>
  </ul>

    <h2>Informacje o zadaniu</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
