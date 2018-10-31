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

  <h2>Grupy w klasie</h2>
  <table id="groups">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/grupa/sortuj/subject_id') }}">przedmiot</a></th>
        <th><a href="{{ url('/grupa/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/grupa/sortuj/date_end') }}">data końcowa</a></th>
        <th>uwagi</th>
        <th><a href="{{ url('/grupa/sortuj/level') }}">poziom</a></th>
        <th>godziny</th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>


    @foreach($grade->groups as $groupClass)
      <tr>
        <td><a href="{{ route('grupa.show', $groupClass->group_id) }}">{{ $groupClass->group_id }}</a></td>
        <td><a href="{{ route('przedmiot.show', $groupClass->group->subject_id) }}"> {{ $groupClass->group->subject->name }}</a></td>
        <td>{{ $groupClass->group->date_start }}</td>
        <td>{{ $groupClass->group->date_end }}</td>
        <td>{{ $groupClass->group->comments }}</td>
        <td>{{ $groupClass->group->level }}</td>
        <td>{{ $groupClass->group->hours }}</td>
        <td>{{ $groupClass->group->created_at }}</td>
        <td>{{ $groupClass->group->updated_at }}</td>
        <td><a href="{{ route('grupa.edit', $groupClass->group_id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa.destroy', $groupClass->group_id) }}" method="post" id="delete-form-{{$groupClass->group_id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

    <tr class="create">
      <td colspan="11"><a href="{{ route('grupa.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
    </tr>
    </tbody>
  </table>
@endsection