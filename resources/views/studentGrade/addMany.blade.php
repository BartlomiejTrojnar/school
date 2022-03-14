<?php /*
@extends('layouts.app')

@section('css')
   <link rel="stylesheet" href="{{ asset('public/css/studentGradeAddMany.css') }}" type="text/css" media="all"  />
@endsection

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('public/js/grade/studentGradeAddMany.js') }}"></script>
@endsection

@section('header')
   <h1>Dodawanie uczniów do klasy</h1>
@endsection

@section('main-content')
   <div id="studentsList">
      <label for="year_of_birth">rok urodzenia</label>
      <input id="year_of_birth" type="text" name="year_of_birth" value="2003" size="4" maxlength="4" />
      <ol>
         @foreach($students as $student)
            @if($student->PESEL && count($student->grades)==0)
               <li data-student_id="{{$student->id}}" data-pesel="{{$student->PESEL}}">{{$student->first_name}} {{$student->last_name}}</li>
            @endif
         @endforeach
      </ol>
   </div>

   <div id="studentGradesList">
      {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
      <input type="text" name="grade_id" value="{{$grade->id}}" readonly="readonly" size="3" /><br />
      <label for="dateStart">data początkowa</label>
      <input id="dateStart" type="date" name="dateStart" size="8" maxlength="10" value="{{ $lastRecord->date_start }}" />
      <label for="dateEnd">data końcowa</label>
      <input type="date" name="dateEnd" size="8" maxlength="10" id="dateEnd" value="{{ $lastRecord->date_end }}" />
      <ol>
         @foreach($studentGrades as $sg)
            <li>{{$sg->student->first_name}} {{$sg->student->last_name}}</li>
         @endforeach
      </ol>
   </div>
@endsection
*/ ?>