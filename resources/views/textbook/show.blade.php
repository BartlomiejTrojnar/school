<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.10.2021 ********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('podrecznik.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('podrecznik.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $textbook->author }} {{ $textbook->title }}</h1>
   <input type="hidden" id="textbook_id" value="{{ $textbook->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('podrecznik/'.$textbook->id.'/showInfo') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('podrecznik.index') }}">spis podręczników <i class='fa fa-undo'></i></a></li>
   </ul>
   <?php echo $subView; ?>
@endsection