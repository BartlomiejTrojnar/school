<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 20.11.2021 *********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/examDescription/index.js') }}"></script>
@endsection

@section('header')
   <h1>Opisy egzaminów maturalnych</h1>
@endsection

@section('main-content')
   @if( !empty( $links ) )
      {!! $examDescriptions->render() !!}
   @endif

   <table id="examDescriptions">
      <thead>
         <tr>
            <th>id</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"sesja",        "routeName"=>"examDescription.orderBy", "field"=>"session_id", "sessionVariable"=>"ExamDescriptionOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"przedmiot",    "routeName"=>"examDescription.orderBy", "field"=>"subject_id", "sessionVariable"=>"ExamDescriptionOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"typ egzaminu", "routeName"=>"examDescription.orderBy", "field"=>"type", "sessionVariable"=>"ExamDescriptionOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"poziom",       "routeName"=>"examDescription.orderBy", "field"=>"level", "sessionVariable"=>"ExamDescriptionOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"max punktów",  "routeName"=>"examDescription.orderBy", "field"=>"max_points", "sessionVariable"=>"ExamDescriptionOrderBy"]);
            ?>
            <th>liczba zdających</th>
            <th>wprowadzono</th>
            <th>aktualizacja</th>
            <th>zmień / usuń</th>
         </tr>

         <tr>
            <td></td>
            <td><?php  print_r($sessionSelectField);  ?></td>
            <td><?php  print_r($subjectSelectField);  ?></td>
            <td><?php  print_r($examTypeSelectField);  ?></td>
            <td><?php  print_r($levelSelectField);  ?></td>
            <td colspan="5"></td>
         </tr>
      </thead>

      <tbody>
         <?php 
            if(isset($_GET['page']))   $count = ($_GET['page']-1)*25;
            else  $count = 0;
         ?>
         @foreach($examDescriptions as $examDescription)
            <tr data-exam_description_id="{{$examDescription->id}}">
               <td><a href="{{ route('opis_egzaminu.show', $examDescription->id) }}">{{ ++$count }}</a></td>
               <td><a href="{{ route('sesja.show', $examDescription->session_id) }}">
                  @if(!empty($examDescription->session->year))
                     {{ $examDescription->session->year }} {{ $examDescription->session->type }}
                  @endif
               </a></td>
               <td><a href="{{ route('przedmiot.show', $examDescription->subject_id) }}">
                  {{ $examDescription->subject->name }}
               </a></td>
               <td>{{ $examDescription->type }}</td>
               <td>{{ $examDescription->level }}</td>
               <td>{{ $examDescription->max_points }}</td>
               <td class="c">{{ count($examDescription->exams) }}</td>
               <td class="c small">{{ substr($examDescription->created_at, 0, 10) }}</td>
               <td class="c small">{{ substr($examDescription->updated_at, 0, 10) }}</td>

               <!-- modyfikowanie i usuwanie -->
               <td class="destroy edit c">
                  <button class="edit btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create"><td colspan="10">
            <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            <input type="hidden" name="lp" value="{{$count+1}}" />
         </td></tr>
      </tbody>
   </table>
@endsection