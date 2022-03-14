@extends('layouts.app')
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->

@if( !empty($java_script) )
   @section('java-script')
       <script language="javascript" type="text/javascript" src="{{ asset('public/js/'.$java_script) }}"></script>
   @endsection
@endif

@section('header')
  <aside id="arrow_left">
    <a href="{{ route('zadanie.show', $previous) }}">
      <i class='fa fa-chevron-left'></i>
    </a>
  </aside>
  <aside id="arrow_right">
    <a href="{{ route('zadanie.show', $next) }}">
      <i class='fa fa-chevron-right'></i>
    </a>
  </aside>
  <h1>{{ $task->name }}</h1>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showInfo') }}">informacje <i class='fas fa-info-circle'></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/showRatings') }}">oceny <i class='fas fa-tasks'></i></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">powrót <i class='fa fa-undo'></i></a></li>
  </ul>

  <?php
    echo $subView;
  ?>
@endsection