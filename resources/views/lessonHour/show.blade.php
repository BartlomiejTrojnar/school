@extends('layouts.app')

@section('header')
  <h1>{{ $lessonHour->day }} lekcja {{ $lessonHour->lesson_number }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('godzina.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('godzina.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
    <p>{{ $lessonHour->day }} lekcja {{ $lessonHour->lesson_number }}</p>
    <p>{{ substr($lessonHour->start_time, 0, 5) }} - {{ substr($lessonHour->end_time, 0, 5) }}</p>

    <p><a href="{{ route('godzina.index') }}">powrót</a></p>
@endsection