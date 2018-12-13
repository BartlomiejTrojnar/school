@extends('layouts.app')
@section('header')
  <h1>Modyfikacja zadania</h1>
@endsection

@section('main-content')
  <form action="{{ route('zadanie.update', $task->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" value="{{$task->name}}" size="60" maxlength="150" required /></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="number" name="points" value="{{$task->points}}" min="1" max="1000" required /></td>
      </tr>
      <tr>
        <th><label for="importance">waga</label></th>
        <td><input type="text" name="importance" value="{{$task->importance}}" size="3" maxlength="4" required /></td>
      </tr>
      <tr>
        <th><label for="sheet_name">nazwa arkusza</label></th>
        <td><input type="text" name="sheet_name" value="{{$task->sheet_name}}" size="12" maxlength="20" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="{{ route('zadanie.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection