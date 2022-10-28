<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.10.2022 *********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection
@section('java-script')
   <script src="{{ asset('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('grupa.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('grupa.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>

   <h1>
      @if($year==0)
         <?php $year=date('Y'); ?>
      @endif
      {{$year - $group->grades[0]->grade->year_of_beginning}}@foreach($group->grades as $groupGrade){{$groupGrade->grade->symbol}}@endforeach
      {{$group->subject->name}}
      @if($group->level == "rozszerzony") R
      @elseif($group->level == "podstawowy") P
      @elseif($group->level == "nieokreślony") -
      @else {{$group->level}}
      @endif
      {{$group->comments}}
      <span style="font-size: 0.5em;">
         [<time id="groupStart">{{$group->start}}</time> : <time id="groupEnd">{{$group->end}}</time>]
         @foreach($group->teachers as $groupTeacher)
            /{{$groupTeacher->teacher->first_name}} {{$groupTeacher->teacher->last_name}}/
         @endforeach
      </span>
   </h1>
   <input type="hidden" id="group_id" value="{{ $group->id }}" />
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/info') }}">informacje <i class='fas fa-info-circle'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/uczniowie') }}">uczniowie <i class='fa fa-users'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/planlekcji') }}">plan lekcji <i class="fa fa-calendar"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ url('grupa/'.$group->id.'/lekcje') }}">lekcje <i class="fa fa-calendar"></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('grupa.index') }}">powrót <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php echo $subView; ?>
@endsection