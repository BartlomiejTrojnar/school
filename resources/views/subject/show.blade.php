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
  <h2>nauczyciele</h2>
  <h2>grupy</h2>
  <h2>podręczniki</h2>
@endsection