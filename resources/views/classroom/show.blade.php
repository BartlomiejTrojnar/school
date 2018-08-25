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
  <h2>sale lekcyjne</h2>
  <table>
    <tr>
      <th>lp</th>
      <th>nazwa</th>
      <th>pojemność</th>
      <th>piętro</th>
      <th>rząd</th>
      <th>kolumna</th>
      <th colspan="2">+/-</th>
    </tr>
    <tr>
      <td>{{ $classroom->id }}</td>
      <td>{{ $classroom->name }}</td>
      <td>{{ $classroom->capacity }}</td>
      <td>{{ $classroom->floor }}</td>
      <td>{{ $classroom->line }}</td>
      <td>{{ $classroom->column }}</td>
      <td><a href="{{ route('sala.edit', $classroom->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" /></a></td>
      <td><a href="{{ route('sala.destroy', $classroom->id) }}"><img class="destroy" src="{{ asset('css/minus.png') }}" /></a></td>
    </tr>
  </table>
@endsection