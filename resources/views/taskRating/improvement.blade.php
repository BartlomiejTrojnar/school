@extends('layouts.app')
@section('header')
  <h1>Poprawa oceny za zadanie</h1>
@endsection

@section('main-content')
  <form action="{{ route('ocena_zadania.updateLotTaskRatings') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th>lp</th>
        <th>zadanie</th>
        <th>uczeń</th>
        <th>termin</th>
        <th>data realizacji</th>
        <th>wersja</th>
        <th>waga</th>
        <th>data oceny</th>
        <th>punkty</th>
        <th>ocena</th>
        <th>uwagi</th>
        <th>dziennik</th>
        <th>data dziennika</th>
      </tr>

      <?php $count=0; ?>
      @foreach($taskRatings as $taskRating)
        <tr>
          <td><input type="hidden" name="task_rating_id{{$taskRating->id}}" value="{{ $taskRating->id }}" />{{ ++$count }}</td>
          <td>{{ $taskRating->task->name }}</td>
          <td>{{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}</td>
          <td><input type="date" name="deadline{{$taskRating->id}}" value="{{ substr($taskRating->deadline, 0, 10) }}" /></td>
          <td><input type="date" name="implementation_date{{$taskRating->id}}" value="{{ substr($taskRating->implementation_date, 0, 10) }}" /></td>
          <td><input type="number" size="2" maxsize="2" name="version{{$taskRating->id}}" value="{{ $taskRating->version }}" /></td>
          <td><input type="text" size="3" maxsize="3" name="importance{{$taskRating->id}}" value="{{ $taskRating->importance }}" /></td>
          <td><input type="date" name="rating_date{{$taskRating->id}}" value="{{ substr($taskRating->rating_date, 0, 10) }}" /></td>
          <td><input type="text" size="3" maxsize="3" name="points{{$taskRating->id}}" value="{{ $taskRating->points }}" /></td>
          <td><input type="text" size="3" maxsize="3" name="rating{{$taskRating->id}}" value="{{ $taskRating->rating }}" /></td>
          <td><input type="text" size="20" maxsize="20" name="comments{{$taskRating->id}}" value="{{ $taskRating->comments }}" /></td>
          <td class="c">
            @if($taskRating->diary)
              <input type="checkbox" name="diary{{$taskRating->id}}" checked />
            @else
              <input type="checkbox" name="diary{{$taskRating->id}}" />
            @endif
          </td>
          <td><input type="date" name="entry_date{{$taskRating->id}}" value="{{ substr($taskRating->entry_date, 0, 10) }}" /></td>
        </tr>
      @endforeach

      <tr class="submit"><td colspan="13">
          <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection