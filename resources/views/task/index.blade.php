@extends('layouts.app')
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/task/operations.js') }}"></script>
@endsection

@section('header')
   <h1>Zadania</h1>
@endsection

@section('main-content')
   <table id="tasks">
      <thead>
         <tr>
            <th>lp</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"zadanie.orderBy", "field"=>"name", "sessionVariable"=>"TaskOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"zadanie.orderBy", "field"=>"points", "sessionVariable"=>"TaskOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"waga", "routeName"=>"zadanie.orderBy", "field"=>"importance", "sessionVariable"=>"TaskOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"arkusz", "routeName"=>"zadanie.orderBy", "field"=>"sheet_name", "sessionVariable"=>"TaskOrderBy"]);
            ?>
            <th>liczba poleceń</th>
            <th>liczba ocen</th>
            <th>wprowadzono</th>
            <th>aktualizacja</th>
            <th>zmień / usuń</th>
         </tr>
      </thead>

      <tbody>
         <?php $count=0;  if(isset($_GET['page'])) $page = $_GET['page']; else $page=1; ?>
         @foreach($tasks as $task)
            <tr data-task_id="{{$task->id}}">
               <td>{{ ++$count }}</td>
               <td><a href="{{ route('zadanie.show', $task->id) }}">{{ $task->name }}</a></td>
               <td>{{ $task->points }}</td>
               <td>{{ $task->importance }}</td>
               <td>{{ $task->sheet_name }}</td>
               <td>{{ count($task->commands) }}</td>
               <td>{{ count($task->taskRatings) }}</td>
               <td class="c small">{{ substr($task->created_at, 0, 10) }}</td>
               <td class="c small">{{ substr($task->updated_at, 0, 10) }}</td>

               <td class="edit destroy c">
                  <button class="edit btn btn-primary" data-task_id="{{ $task->id }}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-task_id="{{ $task->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create"><td colspan="10">
            <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            <input type="hidden" name="lp" value="{{$page*25-25+$count+1}}" />
         </td></tr>
      </tbody>
   </table>
@endsection