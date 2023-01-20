<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('rok_szkolny.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('rok_szkolny.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>rok szkolny {{ substr($schoolYear->date_start, 0, 4) }}/{{ substr($schoolYear->date_end, 0, 4) }}</h1>
   <input id="school_year_id" type="hidden" value="{{ $schoolYear->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/info') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/klasy') }}">klasy <i class='fas fa-user-graduate'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/uczniowie') }}">uczniowie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/podreczniki') }}">podręczniki <i class='fas fa-book'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/nauczyciele') }}">nauczyciele <i class='fas fa-chalkboard-teacher'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/grupy') }}">grupy <i class='fas fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('rok_szkolny.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php echo $subView; ?>
@endsection