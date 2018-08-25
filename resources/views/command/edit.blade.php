@extends('layouts.app')
@section('header')
  <h1>Zamiana danych polecenia</h1>
@endsection

@section('main-content')
  <form action="{{ route('polecenie.update', $command->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="task_id">zadanie</label></th>
        <td><?php  print_r($taskSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="number">numer</label></th>
        <td><input type="number" name="number" min="1" max="20" required value="{{ $command->number }}" /></td>
      </tr>
      <tr>
        <th><label for="command">polecenie</label></th>
        <td><input type="text" name="command" size="15" maxlength="25" required value="{{ $command->command }}" /></td>
      </tr>
      <tr>
        <th><label for="description">opis</label></th>
        <td><input type="text" name="description" size="40" maxlength="65" value="{{ $command->description }}" /></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="number" name="points" min="1" max="20" required value="{{ $command->points }}" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('polecenie.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection