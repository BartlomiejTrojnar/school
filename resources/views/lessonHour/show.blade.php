<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 04.03.2022 ********************** -->
@extends('layouts.app')

@section('css')
   <link href="{{ asset('public/css/lessonHour.css') }}" rel="stylesheet">
@endsection
@section('java-script')
   <script src="{{ asset('public/js/lessonHour.js') }}"></script>
@endsection

@section('header')
   <aside id="arrow_left"><a href="{{ route('godzina.show', $previous) }}"><i class='fa fa-chevron-left'></i></a></aside>
   <aside id="arrow_right"><a href="{{ route('godzina.show', $next) }}"><i class='fa fa-chevron-right'></i></a></aside>
   <h1>{{ $lessonHour->day }} lekcja {{ $lessonHour->lesson_number }}</h1>
@endsection

@section('main-content')
   <?php $dateView = session()->get('dateView'); ?>
   <section id="lessonHourHeader">
      <strong>plan lekcji dla daty:
         <input type="date" id="dateView" value="{{ $dateView }}" />
      </strong>
      <div id="lessonHour_id" hidden>{{$lessonHour->id}}</div>
   </section>
   <aside id="komunikat"></aside>

   <?php
      $year = substr($dateView, 0, 4);
      if( substr($dateView, 5, 2)>8 ) $year++;
   ?>

   <div class="lessons">
      @foreach($lessons as $lesson)
         @if(!$lesson->classroom_id)
            <?php $studyYear = $year - $lesson->group->grades[0]->grade->year_of_beginning; ?>
            <div class="lesson" data-lesson_id="{{$lesson->id}}">
               <span class="lessonDates"><span class="start">{{$lesson->start}}</span> - <span class="end">{{$lesson->end}}</span></span><br />
               <span class="group">
                  {{ $studyYear }}@foreach($lesson->group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
                  {{$lesson->group->subject->name}} 
                  @if($lesson->group->level == 'podstawowy') P @endif
                  @if($lesson->group->level == 'rozszerzony') R @endif
                  @if($lesson->group->level == 'nieokreślony') @endif
                  @if($lesson->group->comments) ({{$lesson->group->comments}}) @endif
                  <code title="liczba uczniów">[{{ count($lesson->group->students) }}]</code>
               </span><br />
               <span class="teacher">
                  @foreach($lesson->group->teachers as $groupTeacher)
                     @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
                        /{{$groupTeacher->teacher->first_name}} {{$groupTeacher->teacher->last_name}}/
                     @endif
                  @endforeach
               </span>
            </div>
         @endif
      @endforeach
   </div>

   <div class="classrooms">
   @foreach($classrooms as $classroom)
      <div class="classroom" data-classroom_id="{{$classroom->id}}">
         <aside class="classroom-name">{{$classroom->name}}</aside>
         @foreach($classroom->lessonPlans as $lesson)
            @if($lesson->lesson_hour_id == $lessonHour->id && $lesson->start<=$dateView && $lesson->end>=$dateView)
            <?php $studyYear = $year - $lesson->group->grades[0]->grade->year_of_beginning; ?>
            <div class="classroom-lesson" data-lesson_id="{{ $lesson->id }}">
               <span class="lessonDates"><span class="start">{{$lesson->start}}</span> : <span class="end">{{$lesson->end}}</span></span><br />
               <span class="group">
                  {{ $studyYear }}@foreach($lesson->group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach
                  {{ $lesson->group->subject->name }}
                  <em style="font-size: 0.8em;">
                     (@if($lesson->group->level == 'nieokreślony')
                     @elseif($lesson->group->level == 'rozszerzony') R 
                     @elseif($lesson->group->level == 'podstawowy') P 
                     @else {{ $lesson->group->level }}
                     @endif
                     {{ $lesson->group->comments }})</em>
                  [{{count($lesson->group->students)}}]
               </span><br />
               <span class="teacher">
                  @foreach($lesson->group->teachers as $groupTeacher)
                     @if($groupTeacher->start<=$dateView && $groupTeacher->end>=$dateView)
                        /{{$groupTeacher->teacher->first_name}} {{$groupTeacher->teacher->last_name}}/
                     @endif
                  @endforeach
               </span>
            </div>
            @endif
         @endforeach
      </div>
   @endforeach
   </div>
@endsection