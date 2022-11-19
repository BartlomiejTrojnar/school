@extends('layouts.app')

@section('css')
   <link rel="stylesheet" href="{{ asset('public/css/teacherPrintOrder.css') }}" type="text/css" media="all"  />
@endsection

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('public/js/teacherPrintOrder.js') }}"></script>
@endsection

@section('header')
   <h1>Kolejność nauczycieli na wydruku</h1>
@endsection

@section('main-content')
   <ol start="0" id="printOrder">
      @for ($i = 0; $i <= 15; $i++)
         <li data-order="{{ $i }}">
            kolejność: {{$i}}
            @foreach($teachers as $teacher)
               @if($teacher->order == $i)
                  <div class="teacher" data-teacher="{{ $teacher->id }}" data-order="{{ $i }}">
                     {{ $teacher->first_name }} {{ $teacher->last_name }}
                  </div>
               @endif
            @endforeach
         </li>
      @endfor
   </ol>
@endsection