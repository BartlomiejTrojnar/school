<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 15.10.2021 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/textbook/textbookChoicesIndex.js') }}"></script>
@endsection

@section('header')
   <h1>Wybory podręczników</h1>
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ route('podrecznik.index') }}">spis podręczników <i class='fa fa-undo'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('wybor_podrecznika.index') }}">wybory podręczników <i class='fa fa-undo'></i></a></li>
   </ul>

   @if( !empty( $links ) )
      {!! $textbookChoices->render() !!}
   @endif

   <table id="textbookChoicesTable">
      <thead>
         <tr>
            <th>lp.</th>
            <th>podręcznik</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"szkoła", "routeName"=>"wybor_podrecznika.orderBy", "field"=>"school_id", "sessionVariable"=>"TextbookChoiceOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"rok szkolny", "routeName"=>"wybor_podrecznika.orderBy", "field"=>"school_year_id", "sessionVariable"=>"TextbookChoiceOrderBy"]);
            ?>
            <th>wybrano dla klasy<br />(rok nauki)</th>
            <th>poziom</th>
            <th>zmień/usuń</th>
         </tr>

         <tr>
            <td></td>
            <td><?php  print_r($subjectSelectField);  ?></td>
            <td><?php  print_r($schoolSelectField);  ?></td>
            <td><?php  print_r($schoolYearSelectField);  ?></td>
            <td><?php  print_r($studyYearSelectField);  ?></td>
            <td><?php  print_r($levelSelectField);  ?></td>
            <td></td>
         </tr>
      </thead>

      <tbody>
         <?php $count=0; ?>
         @foreach($textbookChoices as $textbookChoice)
         <tr data-textbookChoice_id="{{ $textbookChoice->id }}" data-lp="{{++$count}}">
            <td>{{$count}}</td>
            <td><a href="{{ route('podrecznik.show', $textbookChoice->textbook_id) }}">{{$textbookChoice->textbook->title}}</a> {{substr($textbookChoice->textbook->author, 0, 12)}} {{$textbookChoice->textbook->admission}}</td>
            <td><a href="{{ route('szkola.show', $textbookChoice->school_id) }}">{{$textbookChoice->school->name}}</a></td>
            <td><a href="{{ route('rok_szkolny.show', $textbookChoice->school_year_id) }}">
               {{ substr($textbookChoice->schoolYear->date_start, 0, 4) }}/{{ substr($textbookChoice->schoolYear->date_end, 0, 4) }}
            </a></td>
            <td>{{$textbookChoice->learning_year}}</td>
            <td>{{$textbookChoice->level}}</td>
            <td class="edit destroy c" style="width: 150px;">
               <button class="extension btn btn-primary" data-textbookChoice_id="{{ $textbookChoice->id }}"><i class="fa fa-arrow-right" title="przedłuż na następny rok szkolny"></i></button>
               <button class="edit btn btn-primary" data-textbookchoice_id="{{ $textbookChoice->id }}" title="modyfikuj"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-textbookchoice_id="{{ $textbookChoice->id }}"><i class="fas fa-remove" title="usuń"></i></button>
            </td>
         </tr>
         @endforeach

         <tr class="create"><td colspan="7"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
      </tbody>
   </table>
@endsection