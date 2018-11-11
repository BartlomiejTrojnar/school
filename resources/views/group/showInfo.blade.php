@extends('layouts.app')

@section('css')
  <link href="{{ asset('css/group.css') }}" rel="stylesheet">
@endsection
@section('java-script')
  <script src="{{ asset('js/group.js') }}"></script>
@endsection

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
  <table>
    <tr>
      <th>przedmiot</th>
      <td>
        <a href="{{ route('przedmiot.show', $group->subject_id) }}">
          {{ $group->subject->name }}
        </a>
      </td>
    </tr>
    <tr>
      <th>czas życia</th>
      <td>{{ $group->date_start }} - {{ $group->date_end }}</td>
    </tr>
    <tr>
      <th>godziny</th>
      <td data-group_id="{{$group->id}}" data-url="{{ url('grupa') }}">
        <img id="hourSubtract" class="btn btn-success" src="{{ asset('css/minus.png') }}" data-group_id="{{$group->id}}" />
        <span>{{ $group->hours }}</span>
        <img id="hourAdd" class="btn btn-success" src="{{ asset('css/plus.png') }}" data-group_id="{{$group->id}}" />
      </td>
    </tr>
    <tr>
      <th>uwagi</th>
      <td>{{ $group->comments }}</td>
    </tr>
    <tr>
      <th>nauczyciel(e)</th>
      <td>
        @foreach($group->teachers as $groupTeacher)
          <a href="{{ route('nauczyciel.show', $groupTeacher->teacher_id) }}">
            {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}
          </a>
          {{ $groupTeacher->date_start }} {{ $groupTeacher->date_end }}<br />
        @endforeach
      </td>
    </tr>
    <tr>
      <th>klasy</th>
      <td class="grades">
        <aside><a href="{{ url('grupa_klasy/addGrade/'. $group->id) }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></aside>
        @foreach($group->grades as $groupClass)
          <a href="{{ route('klasa.show', $groupClass->grade_id) }}">{{ $groupClass->grade->year_of_beginning }}-{{ $groupClass->grade->year_of_graduation }}{{ $groupClass->grade->symbol }}</a>
          <form action="{{ route('grupa_klasy.destroy', $groupClass->id) }}" method="post" id="delete-form-{{$groupClass->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form><br />
        @endforeach
      </td>
    </tr>
    <tr>
      <th>liczba uczniów</th>
      <td>{{ $group->students->count() }}</td>
    </tr>
  </table>
@endsection