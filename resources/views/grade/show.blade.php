<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 18.09.2021 ********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('klasa.show', $previous) }}"> <i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('klasa.show', $next) }}"> <i class='fa fa-chevron-right'></i></a></aside>
   @if( empty($year) )
      <h1>{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</h1>
   @else
      <h1>{{ $year-$grade->year_of_beginning }}{{ $grade->symbol }}</h1>
   @endif
   <input id="grade_id" type="hidden" name="grade_id" value="{{ $grade->id }}" />
@endsection


@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showInfo') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudents') }}">uczniowie w klasie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showStudentsAll') }}">uczniowie klasy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showNumbers') }}">numery w klasie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showGroups') }}">grupy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showLessonPlan') }}">plan lekcji <i class="fa fa-calendar"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTeachers') }}">nauczyciele <i class='fas fa-chalkboard-teacher'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showTasks') }}">zadania <i class='fa fa-tasks'></i></a></li>
      <?php /*
         <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showEnlargements') }}">rozszerzenia</a></li>
      */ ?>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showRatings') }}">oceny <i class="fa fa-star-half-empty"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/showDeclarations') }}">deklaracje <i class="far fa-newspaper"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection