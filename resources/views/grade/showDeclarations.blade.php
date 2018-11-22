@extends('layouts.app')

@section('header')
  <h1>{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('klasa.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('klasa.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents2') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showEnlargements') }}">rozszerzenia</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showRatings') }}">oceny</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showDeclarations') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTasks') }}">zadania</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">powrót</a></li>
  </ul>

  <h2>Deklaracje maturalne w klasie</h2>
  <table id="declarations">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/deklaracja/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/session_id') }}">sesja</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/application_number') }}">numer zgłoszenia</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/student_code') }}">kod ucznia</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>

    <tbody>
    @foreach($declarations as $declaration)
      <tr>
        <td><a href="{{ route('deklaracja.show', $declaration->id) }}">{{ $loop->iteration }}</a></td>
        <td><a href="{{ route('uczen.show', $declaration->student_id) }}">{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</a></td>
        <td><a href="{{ route('sesja.show', $declaration->session_id) }}">{{ $declaration->session->year }} {{ $declaration->session->type }}</a></td>
        <td>{{ $declaration->application_number }}</td>
        <td>{{ $declaration->student_code }}</td>
        <td>{{ $declaration->created_at }}</td>
        <td>{{ $declaration->updated_at }}</td>
        <td><a href="{{ route('deklaracja.edit', $declaration->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('deklaracja.destroy', $declaration->id) }}" method="post" id="delete-form-{{$declaration->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="9"><a href="{{ route('deklaracja.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection
