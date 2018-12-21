@extends('layouts.app')

@section('header')
  <aside id="strzalka_l">
    <a href="{{ route('szkola.show', $previous) }}">
      <img src="{{ asset('css/strzalka_lewa.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('szkola.show', $next) }}">
      <img src="{{ asset('css/strzalka_prawa.png') }}" alt="nastepna">
    </a>
  </aside>
  <h1>{{ $school->name }}</h1>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('szkola.index') }}">powrót</a></li>
  </ul>

  <?php
    echo $subView;
  ?>
@endsection
