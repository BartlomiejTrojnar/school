@extends('layouts.app')

@section('header')
  <h1>{{ $lesson->group->subject->name }} {{ $lesson->teacher->last_name }} {{ $lesson->number }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('lekcja.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('lekcja.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
    <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('lekcja/'.$lesson->id.'/show') }}">informacje</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('lekcja.index') }}">powrót</a></li>
    </ul>

    <h2>Informacje o lekcji</h2>

    <table>
      <tr><th>grupa</th><td>{{ $lesson->group->subject->name }} <em>(jakie klasy?)</em></td></tr>
      <tr><th>nauczyciel</th><td>{{ $lesson->teacher->first_name }} {{ $lesson->teacher->last_name }}</td></tr>
      <tr><th>data</th><td>{{ $lesson->lesson_date }}</td></tr>
      <tr><th>czas lekcji</th><td>{{ $lesson->lesson_length }}</td></tr>
      <tr><th>numer</th><td>{{ $lesson->number }}</td></tr>
      <tr><th>temat wpisany</th><td>{{ $lesson->topic_entered }}</td></tr>
      <tr><th>temat zrealizowany</th><td>{{ $lesson->topic_realized }}</td></tr>
      <tr><th>uwagi</th><td>{{ $lesson->comments }}</td></tr>
      <tr><th>wpisano</th><td>{{ $lesson->created_at }}</td></tr>
      <tr><th>zaktualizowano</th><td>{{ $lesson->updated_at }}</td></tr>
    </table>
@endsection