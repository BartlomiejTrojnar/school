@extends('layouts.app')

@section('header')
  <h1>{{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('uczen.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('uczen.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showEnlargements') }}">rozszerzenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showTasks') }}">zadania</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showDeclarations') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showExams') }}">egzaminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('uczen.index') }}">powrót</a></li>
  </ul>

  <h2>Plan lekcji ucznia</h2>
  <table>
    <tr>
      <th>godziny</th>
      <th>poniedziałek</th>
      <th>wtorek</th>
      <th>środa</th>
      <th>czwartek</th>
      <th>piątek</th>
    </tr>
    @foreach($lessonHours as $hour)
      <tr>
        <td>{{ $loop->iteration }} {{ substr($hour->start_time, 0, 5) }}</td>
        <td data-hour_id="{{ $hour->id }}"></td>
        <td data-hour_id="{{ $hour->id+9 }}"></td>
        <td data-hour_id="{{ $hour->id+18 }}"></td>
        <td data-hour_id="{{ $hour->id+27 }}"></td>
        <td data-hour_id="{{ $hour->id+36 }}"></td>
      </tr>
    @endforeach
  </table>
@endsection
