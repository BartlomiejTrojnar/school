@extends('layouts.app')
@section('header')
  <h1>Zmiana ocen za zadanie</h1>
@endsection

@section('main-content')
  <form action="{{ route('ocena_zadania.updateLotRatingDates') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th>lp</th>
        <th>zadanie</th>
        <th>uczeń</th>
        <th>data realizacji</th>
        <th>punkty</th>
        <th>ocena</th>
        <th>data oceny</th>
      </tr>

      @foreach($taskRatings as $taskRating)
        <tr>
          <td>{{ $taskRating->id }}</td>
          <td>{{ $taskRating->task->name }}</td>
          <td>{{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}</td>
          <td>{{ $taskRating->implementation_date }}</td>
          <td>{{ $taskRating->points }}</td>
          <td class="c">{{ $taskRating->rating }}</td>
          <td><input type="date" name="rating_date{{$taskRating->id}}" value="{{ substr($taskRating->rating_date, 0, 10) }}" /></td>
        </tr>
      @endforeach

      <tr class="submit"><td colspan="7">
          <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection