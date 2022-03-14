<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 27.10.2021 *********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('sesja.show', $previous) }}"> <i class='fa fa-chevron-left'></i> </a></aside>
   <aside id="arrow_right"><a href="{{ route('sesja.show', $next) }}"> <i class='fa fa-chevron-right'></i> </a></aside>
   <h1>Sesja maturalna: {{ $session->year }} {{$session->type}}</h1>
   <input type="hidden" id="session_id" value="{{ $session->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showInfo') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showExamDescriptions') }}">opisy egzaminów <i class="fas fa-atlas"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showDeclarations') }}">deklaracje <i class="far fa-newspaper"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showTerms') }}">terminy <i class='fa fa-list'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('sesja.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>
   <?php  echo $subView;  ?>
@endsection