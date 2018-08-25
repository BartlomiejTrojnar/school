@extends('layouts.app')
@section('header')
  <h1>Dodawanie godziny lekcyjnej</h1>
@endsection

@section('main-content')
  <form action="{{ route('godzina.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="day">dzień</label></th>
        <td><input type="text" name="day" size="10" maxlength="12" required autofocus /></td>
      </tr>
      <tr>
        <th><label for="lesson_number">numer</label></th>
        <td><input type="number" name="lesson_number" min="1" max="10" required /></td>
      </tr>
      <tr>
        <th><label for="start_time">czas rozpoczęcia</label></th>
        <td><input type="time" name="start_time" required /></td>
      </tr>
      <tr>
        <th><label for="end_time">czas zakończenia</label></th>
        <td><input type="time" name="end_time" required /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('godzina.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection