@extends('layouts.app')

@section('header')
  <h1>{{ $group->id }} {{ $group->subject->name }} {{ $group->date_start }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('grupa.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('grupa.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('grupa.index') }}">powrót</a></li>
  </ul>

  <h2>Informacje</h2>
  <div style="background: yellow; color: red; border: 3px solid red; padding: 50px; text-align: center; font-size: x-large;">Widok w budowie</div>

  <table>
    <tr>
      <th>przedmiot</th>
      <td>{{ $group->subject->name }}</td>
    </tr>
    <tr>
      <th>czas życia</th>
      <td>{{ $group->date_start }} - {{ $group->date_end }}</td>
    </tr>
    <tr>
      <th>godziny</th>
      <td>{{ $group->hours }}</td>
    </tr>
    <tr>
      <th>uwagi</th>
      <td>{{ $group->comments }}</td>
    </tr>
    <tr>
      <th>nauczyciel(e)</th>
      <td>
        @foreach($group->teachers as $groupTeacher)
          {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }} {{ $groupTeacher->date_start }} {{ $groupTeacher->date_end }}<br />
        @endforeach
      </td>
    </tr>
    <tr>
      <th>klasy</th>
      <td>
        @foreach($group->grades as $groupClass)
          {{ $groupClass->id }}<br />
        @endforeach
      </td>
    </tr>
    <tr>
      <th>liczba uczniów</th>
      <td>{{ $group->updated_at }}</td>
    </tr>
  </table>
@endsection