@extends('layouts.app')
@section('header')
  <aside id="arrow_left">
    <a href="{{ route('ocena_zadania.show', $previous) }}">
      <i class='fa fa-chevron-left'></i>
    </a>
  </aside>
  <aside id="arrow_right">
    <a href="{{ route('ocena_zadania.show', $next) }}">
      <i class='fa fa-chevron-right'></i>
    </a>
  </aside>
  <h1>{{ $taskRating->task->name }} <small>({{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }} - {{ $taskRating->version }})</small></h1>
@endsection

@section('main-content')
  <form action="{{ route('ocena_zadania.update', $taskRating->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="deadline">termin</label></th>
        <td>
          <input type="date" name="deadlineDate" value="{{ substr($taskRating->deadline, 0, 10) }}" />
          <input type="time" name="deadlineTime" value="{{ substr($taskRating->deadline, 11, 5) }}" />
        </td>
      </tr>
      <tr>
        <th><label for="implementation_date">data wykonania</label></th>
        <td>
          <input type="date" name="implementationDate" value="{{ substr($taskRating->implementation_date, 0, 10) }}" />
          <input type="time" name="implementationTime" value="{{ substr($taskRating->implementation_date, 11, 5) }}" />
        </td>
      </tr>
      <tr>
        <th><label for="importance">waga</label></th>
        <td><input type="text" name="importance" value="{{$taskRating->importance}}" size="3" maxlength="4" required /></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="text" name="points" value="{{$taskRating->points}}" size="3" maxlength="4" /></td>
      </tr>
      <tr>
        <th><label for="rating_date">data oceny</label></th>
        <td>
          <input type="date" name="ratingDate" value="{{ substr($taskRating->rating_date, 0, 10) }}" />
          <input type="time" name="ratingTate" value="{{ substr($taskRating->rating_date, 11, 5) }}" />
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
        <td><input type="checkbox" name="diary" value="{{$taskRating->diary}}" /></td>
      </tr>
      <tr>
        <th><label for="entry_date">data dziennika</label></th>
        <td>
          <input type="date" name="entryDate" value="{{ substr($taskRating->entry_date, 0, 10) }}" />
          <input type="time" name="entryTime" value="{{ substr($taskRating->entry_date, 11, 5) }}" />
        </td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="student_id" value="{{$taskRating->student_id}}" />
          <input type="hidden" name="task_id" value="{{$taskRating->task_id}}" />
          <input type="hidden" name="version" value="{{$taskRating->version}}" />

          <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit" class="btn btn-primary">zapisz zmiany</button>
          <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">anuluj</a>
      </tr>
    </table>
  </form>
@endsection