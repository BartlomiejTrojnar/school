@extends('layouts.app')

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

  <h2>Uczniowie</h2>
  <ul id="groupStudents">
  @foreach($groupStudents as $groupStudent)
    <li class="{{ $groupStudent->student->sex }}" data-id="{{ $groupStudent->id }}" data-student_id="{{ $groupStudent->student_id }}">
        <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
        <button><a href="{{ route('grupa_uczniowie.edit', $groupStudent->id) }}">
          <img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]" />
        </a></button>
        <span class="uczen">
          @foreach($groupStudent->student->grades as $studentGrade)
            {{ substr( session()->get('dateSession'), 0, 4) - $studentGrade->grade->year_of_beginning }}{{ $studentGrade->grade->symbol }}
            {{ $studentGrade->number }}
          @endforeach
          <a href="{{ route('uczen.show', $groupStudent->student_id) }}">{{ $groupStudent->student->first_name }} {{ $groupStudent->student->last_name }}</a>
        </span>
        <span class="data_start">{{ $groupStudent->date_start }}</span> :
        <span class="data_end">{{ $groupStudent->date_end }}</span>
    </li>
  @endforeach
@endsection
