@extends('layouts.app')

@section('header')
  <aside id="arrow_left">
    <a href="{{ route('przedmiot.show', $previous) }}">
      <i class='fa fa-chevron-left'></i>
    </a>
  </aside>
  <aside id="arrow_right">
    <a href="{{ route('przedmiot.show', $next) }}">
      <i class='fa fa-chevron-right'></i>
    </a>
  </aside>
  <h1>{{ $subject->name }}</h1>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showInfo') }}">informacje <i class='fas fa-info-circle'></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showTeachers') }}">nauczyciele <i class='fas fa-chalkboard-teacher'></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showGroups') }}">grupy <i class='fas fa-users'></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showExamDescriptions') }}">opisy egzaminów <i class="fas fa-atlas"></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showTextbooks') }}">podręczniki <i class='fas fa-book'></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('przedmiot.index') }}">powrót <i class='fa fa-undo'></i></a></li>
  </ul>

  <?php
    echo $subView;
  ?>
@endsection
