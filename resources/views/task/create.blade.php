@extends('layouts.app')
@section('header')
  <h1>Dodawanie zadania</h1>
@endsection

@section('main-content')
  <form action="{{ route('zadanie.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" size="60" maxlength="150" required /></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="number" name="points" min="1" max="1000" required /></td>
      </tr>
      <tr>
        <th><label for="importance">waga</label></th>
        <td><input type="text" name="importance" size="3" maxlength="4" required /></td>
      </tr>
      <tr>
        <th><label for="sheet_name">nazwa arkusza</label></th>
        <td><input type="text" name="sheet_name" size="12" maxlength="20" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('zadanie.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection