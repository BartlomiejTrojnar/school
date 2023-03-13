@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
   
@endsection

@section('java-script')
   <script language="javascript" type="module" src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('uczen.show', $previous) }}"> <i class='fa fa-chevron-left'></i> </a></aside>
   <aside id="arrow_right"><a href="{{ route('uczen.show', $next) }}"> <i class='fa fa-chevron-right'></i> </a></aside>
   <h1>{{ $student->first_name }} <span class="small">{{ $student->second_name }}</span> {{ $student->last_name }}</h1>
   <input id="student_id" type="hidden" name="student_id" value="{{ $student->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/info') }}">informacje <i class="bi bi-info-circle"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/klasy') }}">klasy <i class="bi bi-mortarboard"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/rozszerzenia') }}">rozszerzenia <i class="bi bi-palette2"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/grupy') }}">grupy <i class="bi bi-people-fill"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/planlekcji') }}">plan lekcji <i class="bi bi-calendar2-range-fill"></i></a></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/zadania') }}">zadania <i class="bi bi-list-task"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/swiadectwa') }}">świadectwa <i class="bi bi-award"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/deklaracje') }}">deklaracje <i class="bi bi-motherboard-fill"></i></a></li>
   <?php /*
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showRatings') }}">oceny</a></li>
   */ ?>
      <li class="nav-item"><a class="nav-link" href="{{ route('uczen.index') }}">powrót <i class="bi bi-arrow-up-square"></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection