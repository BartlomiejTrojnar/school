<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('szkola.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('szkola.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $school->name }}</h1>
   <input id="school_id" type="hidden" value="{{ $school->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showInfo') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showStudents') }}">uczniowie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('szkola/'.$school->id.'/showGrades') }}">klasy</a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('szkola.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php echo $subView; ?>
@endsection