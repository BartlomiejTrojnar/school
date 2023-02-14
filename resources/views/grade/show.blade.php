<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 14.02.2023 ********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script language="javascript" type="module" src="{{ asset('public/js/'.$js) }}"></script>
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
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/info') }}">informacje <i class="bi bi-info-circle"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/uczniowie') }}">uczniowie w klasie <i class="bi bi-people-fill"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/daneuczniow') }}">uczniowie klasy <i class="bi bi-people-fill"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/numery') }}">numery w klasie <i class="bi bi-123"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/grupy') }}">grupy <i class="bi bi-people-fill"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/planlekcji') }}">plan lekcji <i class="bi bi-calendar2-range-fill"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/nauczyciele') }}">nauczyciele <i class="bi bi-tablet-landscape"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/zadania') }}">zadania <i class="bi bi-list-task"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/rozszerzenia') }}">rozszerzenia <i class="bi bi-palette2"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/oceny') }}">oceny <i class="bi bi-star-half"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('klasa/'.$grade->id.'/deklaracje') }}">deklaracje <i class="bi bi-motherboard-fill"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('klasa.index') }}">powrót <i class="bi bi-arrow-up-square"></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection