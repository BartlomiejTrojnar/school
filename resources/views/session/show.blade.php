@extends('layouts.app')
@section('header')
  <h1>Sesja maturalna: {{ $session->year }} {{$session->type}}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('sesja.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('sesja.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <p>Uzupełnić!</p>
@endsection