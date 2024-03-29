@extends('layouts.app')
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 18.01.2023 *********************** -->

@section('java-script')
   <script language="javascript" type="module" src="{{ asset('public/js/bookOfStudent/index.js') }}"></script>
@endsection

@section('header')
   <h1>Księga uczniów</h1>
@endsection

@section('main-content')
   <p class="btn btn-primary" style="float: right;"><a href="{{ route('uczen.search') }}">szukaj</a></p>
   {!! $bookOfStudents->render() !!}
   <table id="bookOfStudents">
      <thead>
         <tr>
            <th>id</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"szkoła", "routeName"=>"ksiega_uczniow.orderBy", "field"=>"school_id",  "sessionVariable"=>"BookOfStudentOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"uczeń",  "routeName"=>"ksiega_uczniow.orderBy", "field"=>"student_id", "sessionVariable"=>"BookOfStudentOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"numer",  "routeName"=>"ksiega_uczniow.orderBy", "field"=>"number",     "sessionVariable"=>"BookOfStudentOrderBy"]);
            ?>
            <th>wpis</th>
            <th>aktualizacja</th>
            <th>zmień / usuń</th>
         </tr>
         <tr>
            <td colspan="2"><?php  print_r($schoolSF);  ?></td>
            <td colspan="5" class="c"></td>
         </tr>
      </thead>

      <tbody>
      <?php $count = 0; ?>
      @foreach($bookOfStudents as $bookOfStudent)
         <tr data-book_of_student_id="{{ $bookOfStudent->id }}">
            @if( !empty($_GET['page']) )
               <td class="lp" style="font-size: small;">{{ $_GET['page']*30-30+(++$count) }}</td>
            @else
               <td class="lp">{{ ++$count }}</td>
            @endif
            <td><a href="{{ route('szkola.show', $bookOfStudent->school_id) }}">{{ $bookOfStudent->school->name }}</a></td>
            <td><a href="{{ route('uczen.show', $bookOfStudent->student_id) }}">{{ $bookOfStudent->student->first_name }} {{ $bookOfStudent->student->last_name }}</a></td>
            <td>{{ $bookOfStudent->number }}</td>
            <td class="small c">{{ substr($bookOfStudent->created_at, 0, 10) }}</td>
            <td class="small c">{{ substr($bookOfStudent->updated_at, 0, 10) }}</td>
            <td class="destroy edit c" style="width: 100px;">
               <button class="edit btn btn-primary"    data-book_of_student_id="{{ $bookOfStudent->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-book_of_student_id="{{ $bookOfStudent->id }}"><i class="fa fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

         <tr class="create"><td colspan="8">
            <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
         </td></tr>
      </tbody>
   </table>
   @if( !empty($_GET['page']) )
      <input id="countRegistrations" type="hidden" value="{{ $_GET['page']*30-30+$count }}" />
   @else
      <input id="countRegistrations" type="hidden" value="{{ count($bookOfStudents)  }}" />
   @endif
@endsection