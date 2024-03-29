@extends('layouts.app')

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('przedmiot.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('przedmiot.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $subject->name }}</h1>
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/info') }}">informacje <i class='fa fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/nauczyciele') }}">nauczyciele <i class='fa fa-chalkboard-teacher'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/grupy') }}">grupy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/opisy-egzaminow') }}">opisy egzaminów <i class="fa fa-atlas"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('przedmiot/'.$subject->id.'/podreczniki') }}">podręczniki <i class='fa fa-book'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('przedmiot.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection