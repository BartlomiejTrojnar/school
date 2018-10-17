@extends('layouts.app')

@section('header')
  <h1>egzamin [{{ $exam->id }}]</h1>
  <aside id="strzalka_l">
    <a href="{{ route('egzamin.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('egzamin.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <h2>informacje o egzaminie</h2>
@endsection