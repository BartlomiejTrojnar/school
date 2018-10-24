@extends('layouts.app')

@section('css')
  <link href="{{ asset('css/taughtSubject.css') }}" rel="stylesheet">
@endsection
@section('java-script')
  <script src="{{ asset('js/taughtSubject.js') }}"></script>
@endsection


@section('header')
  <h1>{{ $subject->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('przedmiot.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('przedmiot.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/showTextbooks') }}">podręczniki</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('przedmiot.index') }}">powrót</a></li>
  </ul>

  <h2>Nauczyciele przedmiotu</h2>
  <div id="subject-id">{{ $subject->id }}</div>
  <form action="{{ route('nauczany_przedmiot.store') }}" method="post" role="form" id="formTaughtSubject" style="display:none;">
    {{ csrf_field() }}
    <input name="teacher_id" />
    <input name="subject_id" />
    <input type="hidden" name="history_view" value="{{ route('nauczyciel.show', $subject->id) }}" />
  </form>
  <div id="url">{{ url('nauczany_przedmiot') }}</div>
  <div id="token">{{ csrf_field() }}</div>


  <section id="subjectTeachersList">
    <h1>Uczący</h1>
    <ul class="list-group">
      @foreach($subjectTeachers as $subjectTeacher)
        <li type="button" class="list-group-item active" data-taught-subject-id="{{ $subjectTeacher->id }}" data-teacher-id="{{ $subjectTeacher->teacher_id }}" data-teacher-name="{{ $subjectTeacher->teacher->first_name }} {{ $subjectTeacher->teacher->last_name }}">
          {{ $subjectTeacher->teacher->first_name }} {{ $subjectTeacher->teacher->last_name }}
          <span class="url">{{ url('nauczany_przedmiot/delete', $subjectTeacher->id) }}</span>
        </li>
      @endforeach
    </ul>
  </section>

  <section id="unlearningTeachersList">
    <h1>Nieuczący</h1>
    <ul class="list-group">
      @foreach($unlearningTeachers as $unlearningTeacher)
        <li type="button" class="list-group-item" data-teacher-id="{{ $unlearningTeacher->id }}">{{ $unlearningTeacher->first_name }} {{ $unlearningTeacher->last_name }}</li>
      @endforeach
    </ul>
  </section>
@endsection