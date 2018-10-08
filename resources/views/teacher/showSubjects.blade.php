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


    <p style="display: none;" id="teacher_id">{{ $teacher->id }}</p>
    <p>{{ $teacher->family_name }}</p>
    <p>{{ $teacher->short }}</p>
    <p>{{ $teacher->degree }}</p>
    <p>{{ $teacher->classroom_id }}</p>
    <p>nauczyciel od @if($teacher->first_year) {{ substr($teacher->first_year->date_start, 0, 4) }}/{{ substr($teacher->first_year->date_end, 0, 4)}} @endif
       do @if($teacher->last_year) {{ substr($teacher->last_year->date_start, 0, 4)}}/{{ substr($teacher->last_year->date_end, 0, 4) }} @endif</p>
    <p>wprowadzono: {{ $teacher->created_at }}, aktualizacja: {{ $teacher->updated_at }}</p>


    <h2>Nauczane przedmioty</h2>

    <form action="{{ route('nauczany_przedmiot.store') }}" method="post" role="form" id="formTaughtSubject" style="display:none;">
      {{ csrf_field() }}
      <input name="teacher_id" />
      <input name="subject_id" />
      <input type="hidden" name="history_view" value="{{ route('nauczyciel.show', $teacher->id) }}" />
    </form>
    <div id="url">{{ url('nauczany_przedmiot') }}</div>
    <div id="token">{{ csrf_field() }}</div>


    <section id="taughtSubjectsList">
      <ul class="list-group">
        @foreach($taughtSubjects as $taughtSubject)
          <li type="button" class="list-group-item active" data-taught-subject-id="{{ $taughtSubject->id }}" data-subject-id="{{ $taughtSubject->subject_id }}" data-subject-name="{{ $taughtSubject->subject->name }}">
            {{ $taughtSubject->subject->name }}
            <span class="url">{{ url('nauczany_przedmiot/delete', $taughtSubject->id) }}</span>
          </li>
        @endforeach
      </ul>
    </section>

    <section id="subjectsList">
      <ul class="list-group">
        @foreach($nonTaughtSubjects as $nonTaughtSubject)
          <li type="button" class="list-group-item" data-subject-id="{{ $nonTaughtSubject->id }}" data-teacher-id="{{ $teacher->id }}">{{ $nonTaughtSubject->name }}</li>
        @endforeach
      </ul>
    </section>

@endsection
