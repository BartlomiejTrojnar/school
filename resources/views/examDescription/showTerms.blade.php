@extends('layouts.app')

@section('header')
  <h1>{{ $examDescription->subject->name }} {{ $examDescription->session->year }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('opis_egzaminu.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('opis_egzaminu.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('opis_egzaminu/'.$examDescription->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('opis_egzaminu/'.$examDescription->id.'/showTerms') }}">terminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('opis_egzaminu/'.$examDescription->id.'/showExams') }}">egzaminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('opis_egzaminu.index') }}">powrót</a></li>
  </ul>

    <h2>Terminy egzaminów dla opisu egzaminu</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
