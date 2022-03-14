@extends('layouts.app')
@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('public/js/taskRatingEdit.js') }}"></script>
@endsection

@section('header')
   <h1>Dodawanie oceny zadania</h1>
@endsection

@section('main-content')
   <form action="{{ route('ocena_zadania.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <table>
         <tr>
            <th><label for="student_id">uczeń</label></th>
            <td><?php  print_r($studentSelectField);  ?></td>
         </tr>
         <tr>
            <th><label for="task_id">zadanie</label></th>
            <td><?php  print_r($taskSelectField);  ?></td>
         </tr>
         <tr>
            <th><label for="deadline">termin</label></th>
            <td><input type="date" name="deadline" required /></td>
         </tr>
         <tr>
            <th><label for="implementation_date">data wykonania</label></th>
            <td><input type="date" name="implementation_date" /></td>
         </tr>
         <tr>
            <th><label for="version">wersja</label></th>
            <td><input type="number" name="version" size="2" maxlength="1" required /></td>
         </tr>
         <tr>
            <th><label for="importance">waga</label></th>
            <td><input type="text" name="importance" size="3" maxlength="4" required /></td>
         </tr>
         <tr>
            <th><label for="rating_date">data oceny</label></th>
            <td><input type="date" name="rating_date" /></td>
         </tr>
         <tr>
            <th><label for="points">punkty</label></th>
            <td>
               <input type="text" name="points" size="3" maxlength="4" />
               @if($task) z <span id="maxPoints">{{$task->points}}</span> <span id="percent">(0%)</span>@endif
            </td>
         </tr>
         <tr>
            <th><label for="rating">ocena</label></th>
            <td><input type="text" name="rating" size="2" maxlength="2" /></td>
         </tr>
         <tr>
            <th><label for="comments">uwagi</label></th>
            <td><input type="text" name="comments" size="20" maxlength="50" /></td>
         </tr>
         <tr>
            <th><label for="diary">dziennik</label></th>
            <td><input type="checkbox" name="diary" /></td>
         </tr>
         <tr>
            <th><label for="entry_date">data dziennika</label></th>
            <td><input type="date" name="entry_date" /></td>
         </tr>

         <tr class="submit"><td colspan="2">
               <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
               <button class="btn btn-primary" type="submit">dodaj</button>
               <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
         </td></tr>
      </table>
   </form>
@endsection