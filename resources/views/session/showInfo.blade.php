@extends('layouts.app')

@section('header')
  <h1>Sesja maturalna: {{ $session->year }} {{$session->type}}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('sesja.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('sesja.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showExamDescriptions') }}">opisy egzaminów</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showDeclarations') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showTerms') }}">terminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('sesja.index') }}">powrót</a></li>
  </ul>

  <h2>Informacje o sesji</h2>
  <table>
    <tr>
      <th>rok</th>
      <td>{{ $session->year }}</td>
    </tr>
    <tr>
      <th>miesiąc</th>
      <td>{{ $session->type }}</td>
    </tr>
    <tr>
      <th>liczba deklaracji</th>
      <td><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showDeclarations') }}">{{ count($session->declarations )}}</a></td>
    </tr>
    <tr>
      <th>liczba typów egzaminów</th>
      <td><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showExamDescriptions') }}">{{ count($session->examDescriptions) }}</a></td>
    </tr>
    <tr>
      <th>liczba terminów</th>
      <td><em>do realizacji</em></td>
    </tr>
    <tr>
      <th>liczba egzaminów</th>
      <td><em>do realizacji</em></td>
    </tr>
  </table>
@endsection
