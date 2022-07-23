<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 23.07.2022 ********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('nauczyciel.show', $previous) }}"> <i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('nauczyciel.show', $next) }}"> <i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $teacher->degree }} {{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
   <input type="hidden" id="teacher_id" value="{{ $teacher->id }}" />
@endsection


@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/info') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/przedmioty') }}">przedmioty <i class='fas fa-chalkboard'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/grupy') }}">grupy <i class='fas fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/klasy') }}">klasy <i class='fas fa-user-graduate'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('nauczyciel/'.$teacher->id.'/planlekcji') }}">plan lekcji <i class="fa fa-calendar"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('nauczyciel.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection