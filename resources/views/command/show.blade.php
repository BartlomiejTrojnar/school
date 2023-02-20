@extends('layouts.app')

@section('header')
   <aside id="arrow_left"><a href="{{ route('polecenie.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('polecenie.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $command->command }} ({{ $command->task->name }})</h1>
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('polecenie/'.$command->id.'/info') }}">informacje <i class='fa fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('polecenie/'.$command->id.'/oceny') }}">oceny polecenia <i class="fa fa-bar-chart"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('polecenie.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>
   <?php  echo $subView; ?>
@endsection