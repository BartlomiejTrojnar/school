@extends('layouts.app')
@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('public/js/taskRatingEdit.js') }}"></script>
@endsection

@section('header')
  <h1>Modyfikacja oceny zadania</h1>
@endsection

@section('main-content')
  <form action="{{ route('ocena_zadania.update', $taskRating->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
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
        <td><input type="date" name="deadline" value="{{substr($taskRating->deadline, 0, 10)}}" required /></td>
      </tr>
      <tr>
        <th><label for="implementation_date">data wykonania</label></th>
        <td><input type="date" name="implementation_date" value="{{substr($taskRating->implementation_date, 0, 10)}}" /></td>
      </tr>
      <tr>
        <th><label for="version">wersja</label></th>
        <td><input type="number" name="version" value="{{$taskRating->version}}" size="1" maxlength="1" required /></td>
      </tr>
      <tr>
        <th><label for="importance">waga</label></th>
        <td><input type="text" name="importance" value="{{$taskRating->importance}}" size="3" maxlength="4" required /></td>
      </tr>
      <tr>
        <th><label for="rating_date">data oceny</label></th>
        <td><input type="date" name="rating_date" value="{{substr($taskRating->rating_date, 0, 10)}}" /></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td>
          <input type="text" name="points" value="{{$taskRating->points}}" size="3" maxlength="4" /> z <span id="maxPoints">{{$taskRating->task->points}}</span>
          <span id="percent">({{$taskRating->points/$taskRating->task->points}}%)</span>
        </td>
      </tr>
      <tr>
        <th><label for="rating">ocena</label></th>
        <td><input type="text" name="rating" value="{{$taskRating->rating}}" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" value="{{$taskRating->comments}}" size="20" maxlength="50" /></td>
      </tr>
      <tr>
        <th><label for="diary">dziennik</label></th>
        <td><input type="checkbox" name="diary" @if($taskRating->diary) checked @endif /></td>
      </tr>
      <tr>
        <th><label for="entry_date">data dziennika</label></th>
        <td><input type="date" name="entry_date" value="{{substr($taskRating->entry_date, 0, 10)}}" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit" class="btn btn-primary">zapisz zmiany</button>
          <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">anuluj</a>
      </tr>
    </table>
  </form>
@endsection