@extends('layouts.app')
@section('header')
  <h1>{{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('nauczyciel.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzedni">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('nauczyciel.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepny">
    </a>
  </aside>
@endsection

@section('main-content')
    <p>{{ $teacher->family_name }}</p>
    <p>{{ $teacher->short }}</p>
    <p>{{ $teacher->degree }}</p>
    <p>{{ $teacher->classroom->name }}</p>
    <p>od {{ $teacher->first_year_id-1 }}/{{$teacher->first_year_id}} do {{$teacher->last_year_id-1}}/{{$teacher->last_year_id}}</p>
    <p>{{ $teacher->order }}</p>
    <p>{{ $teacher->created_at }}</p>
    <p>{{ $teacher->updated_at }}</p>

    <p><a href="{{ route('nauczyciel.index') }}">powrót</a></p>
@endsection