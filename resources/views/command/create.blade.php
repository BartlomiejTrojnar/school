@extends('layouts.app')
@section('header')
  <h1>Dodawanie polecenia</h1>
@endsection

@section('main-content')
  <form action="{{ route('polecenie.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="task_id">zadanie</label></th>
        <td><?php  print_r($taskSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="number">numer</label></th>
        <td><input type="number" name="number" min="1" max="20" required /></td>
      </tr>
      <tr>
        <th><label for="command">polecenie</label></th>
        <td><input type="text" name="command" size="15" maxlength="25" required /></td>
      </tr>
      <tr>
        <th><label for="description">opis</label></th>
        <td><input type="text" name="description" size="40" maxlength="65" /></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="number" name="points" min="1" max="20" required /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection