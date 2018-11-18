@extends('layouts.app')

@section('header')
  <h1>{{ $task->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('zadanie.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('zadanie.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showCommands') }}">polecenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">powrót</a></li>
  </ul>

  <h2>Informacje o zadaniu</h2>
  <table>
    <tr>
      <th>nazwa zadania</th>
      <th>waga</th>
      <th>punkty</th>
      <th>nazwa arkusza<br />w excelu</th>
      <th>utworzono</th>
      <th>aktualizacja</th>
    </tr>
    <tr>
      <td>{{ $task->name }}</td>
      <td>{{ $task->importance }}</td>
      <td>{{ $task->points }}</td>
      <td>{{ $task->sheet_name }}</td>
      <td>{{ $task->created_at }}</td>
      <td>{{ $task->updated_at }}</td>
    </tr>
  </table>
@endsection
