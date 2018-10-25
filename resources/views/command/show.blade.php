@extends('layouts.app')
@section('header')
  <h1>{{ $command->command }} ({{ $command->task->name }})</h1>
  <aside id="strzalka_l">
    <a href="{{ route('polecenie.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('polecenie.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <p><a href="{{ route('polecenie.index') }}">powrót</a></p>
  <table>
    <tr><th>zadanie</th><th>numer</th><th>polecenie</th><th>opis</th><th>punkty</th><th>utworzono</th><th>zaktualizowano</th></tr>
    <tr>
      <td>{{ $command->task->name }}</td>
      <td>{{ $command->number }}</td>
      <td>{{ $command->command }}</td>
      <td>{{ $command->description }}</td>
      <td>{{ $command->points }}</td>
      <td>{{ $command->created_at }}</td>
      <td>{{ $command->updated_at }}</td>
    </tr>
  </table>

  <h2>oceny polecenia</h2>
  <table>
    <tr>
      <th>id</th>
      <th>ocena zadania</th>
      <th>punkty</th>
      <th>utworzono</th>
      <th>zaktualizowano</th>
    </tr>
    @foreach($commandRatings as $commandRating)
      <tr>
        <td>{{ $commandRating->id }}</td>
        <td>{{ $commandRating->task_rating_id }}</td>
        <td>{{ $commandRating->points }}</td>
        <td>{{ $commandRating->created_at }}</td>
        <td>{{ $commandRating->updated_at }}</td>
      </tr>
    @endforeach
  </table>
@endsection