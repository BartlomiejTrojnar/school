<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 24.02.2022 *********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection
@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('sala.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('sala.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>

   <h1>{{ $classroom->name }}</h1>
   <input type="hidden" id="classroom_id" value="{{ $classroom->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showLessonPlan') }}">plan lekcji <i class="fa fa-calendar"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showTerms') }}">terminy <i class='fa fa-list'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showExams') }}">egzaminy <span class="glyphicon glyphicon-list-alt"></span></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('sala.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection