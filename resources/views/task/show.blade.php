@extends('layouts.app')
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 12.11.2022 *********************** -->

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection


@section('header')
   <aside id="arrow_left"><a href="{{ route('zadanie.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('zadanie.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $task->name }}</h1>
   <input id="task_id" type="hidden" name="task_id" value="{{ $task->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/info') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('zadanie/'.$task->id.'/oceny') }}">oceny <i class='fas fa-tasks'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('zadanie.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php echo $subView; ?>
@endsection