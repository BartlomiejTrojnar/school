@extends('layouts.app')

@section('header')
  <h1>{{ $subject->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('przedmiot.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('przedmiot.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showTextbooks') }}">podręczniki</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('przedmiot.index') }}">powrót</a></li>
  </ul>

    <h2>Grupy przedmiotu</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
