@extends('layouts.app')

@section('header')
  <aside id="strzalka_l">
    <a href="{{ route('polecenie.show', $previous) }}">
      <img src="{{ asset('css/strzalka_lewa.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('polecenie.show', $next) }}">
      <img src="{{ asset('css/strzalka_prawa.png') }}" alt="nastepny">
    </a>
  </aside>
  <h1>{{ $command->command }} ({{ $command->task->name }})</h1>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('polecenie/'.$command->id.'/showInfo') }}">informacje</a></li>
<?php /*
    <li class="nav-item"><a class="nav-link" href="{{ url('polecenie/'.$command->id.'/showCommands') }}">oceny polecenia</a></li>
*/ ?>
    <li class="nav-item"><a class="nav-link" href="{{ route('polecenie.index') }}">powrót</a></li>
  </ul>

  <?php
    echo $subView;
  ?>
@endsection