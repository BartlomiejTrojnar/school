@extends('layouts.app')

@section('header')
  <h1>{{ $classroom->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('sala.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('sala.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showTerms') }}">terminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showExams') }}">egzaminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('sala.index') }}">powrót</a></li>
  </ul>

  <h2>Plan lekcji dla sali</h2>
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
