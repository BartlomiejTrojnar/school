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
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/info') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/uczniowie') }}">uczniowie w klasie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/daneuczniow') }}">uczniowie klasy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/numery') }}">numery w klasie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/grupy') }}">grupy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/planlekcji') }}">plan lekcji <i class="fa fa-calendar"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/nauczyciele') }}">nauczyciele <i class='fas fa-chalkboard-teacher'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/zadania') }}">zadania <i class='fa fa-tasks'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/rozszerzenia') }}">rozszerzenia</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/oceny') }}">oceny <i class="fa fa-star-half-empty"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/deklaracje') }}">deklaracje <i class="far fa-newspaper"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection