@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('opis_egzaminu.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('opis_egzaminu.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $examDescription->subject->name }} {{ $examDescription->session->year }}</h1>
   <input type="hidden" id="exam_description_id" value="{{ $examDescription->id }}" />
@endsection


@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('opis_egzaminu/'.$examDescription->id.'/info') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('opis_egzaminu/'.$examDescription->id.'/terminy') }}">terminy <i class='fa fa-list'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('opis_egzaminu/'.$examDescription->id.'/egzaminy') }}">zdający/egzaminy <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('opis_egzaminu.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $subView;  ?>
@endsection