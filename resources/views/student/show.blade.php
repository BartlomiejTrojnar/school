@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left">
      <a href="{{ route('uczen.show', $previous) }}"> <i class='fa fa-chevron-left'></i> </a>
   </aside>
   <aside id="arrow_right">
      <a href="{{ route('uczen.show', $next) }}"> <i class='fa fa-chevron-right'></i> </a>
   </aside>
   <h1>{{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}</h1>
   <input id="student_id" type="hidden" name="student_id" value="{{$student->id}}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/info') }}">informacje  <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/klasy') }}">klasy <i class='fas fa-user-graduate'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/grupy') }}">grupy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/planlekcji') }}">plan lekcji <i class="fa fa-calendar"></i></a></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/zadania') }}">zadania <i class='fa fa-tasks'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/deklaracje') }}">deklaracje <i class="far fa-newspaper"></i></a></li>
   <?php /*
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showEnlargements') }}">rozszerzenia</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('uczen/'.$student->id.'/showRatings') }}">oceny</a></li>
   */ ?>
      <li class="nav-item"><a class="nav-link" href="{{ route('uczen.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection