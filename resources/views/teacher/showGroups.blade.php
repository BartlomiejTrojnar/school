@extends('layouts.app')
@section('css')
  <link href="{{ asset('css/taughtSubject.css') }}" rel="stylesheet">
@endsection

@section('java-script')
  <script src="{{ asset('js/taughtSubject.js') }}"></script>
@endsection

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
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showSubjects') }}">przedmioty</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/showLessonPlans') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('nauczyciel.index') }}">powrót</a></li>
  </ul>


  <h2>Grupy nauczyciela</h2>
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
    @foreach($teacher->groups as $group)
      <tr>
        <td><a href="{{ route('grupa.show', $group->id) }}">{{ $group->id }}</a></td>
        <td><a href="{{ route('przedmiot.show', $group->group->subject_id) }}">{{ $group->group->subject->name }}</a></td>
        <td>{{ $group->date_start }}</td>
        <td>{{ $group->date_end }}</td>
        <td>{{ $group->comments }}</td>
        <td>{{ $group->level }}</td>
        <td>{{ $group->hours }}</td>
        <td>{{ $group->created_at }}</td>
        <td>{{ $group->updated_at }}</td>
        <td><a href="{{ route('grupa.edit', $group->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('grupa.destroy', $group->id) }}" method="post" id="delete-form-{{$group->id}}">
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
