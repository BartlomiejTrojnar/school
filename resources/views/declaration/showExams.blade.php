@extends('layouts.app')

@section('header')
  <h1>{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('deklaracja.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('deklaracja.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('deklaracja/'.$declaration->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('deklaracja/'.$declaration->id.'/showExams') }}">egazminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('deklaracja.index') }}">powrót</a></li>
  </ul>

    <h2>Egzaminy na deklaracji</h2>
    <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>
@endsection
