@extends('layouts.app')

@section('header')
  <h1>{{ $school->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('szkola.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('szkola.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('szkola.index') }}">powrót</a></li>
  </ul>

  <h2>uczniowie w szkole</h2>
  <table>
    <tr>
      <th>Lp</th>
      <th>imiona</th>
      <th>nazwisko</th>
      <th>data urodzenia</th>
    </tr>
    @foreach($students as $student)
      <tr>
        <td>{{ $student->id }}</td>
        <td>{{ $student->first_name }} {{ $student->second_name }}</td>
        <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a> ({{ $student->family_name }})</td>
        <td>{{ $student->PESEL }}</td>
      </tr>
    @endforeach
  </table>
@endsection