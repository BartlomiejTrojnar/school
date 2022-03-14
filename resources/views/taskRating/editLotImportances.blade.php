@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/css/taskRating.css') }}" type="text/css" media="all"  />
@endsection
@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('public/js/taskRating/edit.js') }}"></script>
@endsection


@section('header')
  <h1>Zmiana wag i wersji ocen zadań</h1>
@endsection


@section('main-content')
  <aside>
    <input id="valueToEnter" type="number" />
    <button id="buttonValueToEnterClick" class="btn btn-primary">Wpisz</button>
  </aside>

  <form action="{{ route('ocena_zadania.updateLotImportances') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th>lp</th>
        <th>zadanie</th>
        <th>uczeń</th>
        <th>data realizacji</th>
        <th>wersja</th>
        <th colspan="2">waga</th>
    </tr>

      @foreach($taskRatings as $taskRating)
        <tr class="unlocked" data-taskRating="{{$taskRating->id}}">
          <td>{{ $taskRating->id }}</td>
          <td>{{ $taskRating->task->name }}</td>
          <td>{{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}</td>
          <td>{{ $taskRating->implementation_date }}</td>
          <td>{{ $taskRating->version }}</td>
          <td><input type="text" class="valueInput" name="importance{{$taskRating->id}}" value="{{ $taskRating->importance }}" /></td>
          <td class="status" data-taskRating="{{$taskRating->id}}"><i class='fas fa-hand-point-left'></i></td>
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