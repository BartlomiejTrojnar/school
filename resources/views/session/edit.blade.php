@extends('layouts.app')
@section('header')
  <h1>Zamiana danych sesji</h1>
@endsection

@section('main-content')
  <form action="{{ route('sesja.update', $session->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="year">rok</label></th>
        <td><input type="number" name="year" min="2005" required value="{{ $session->year }}" /></td>
      </tr>
      <tr>
        <th><label for="type">typ</label></th>
        <td><?php  print_r($typeSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
        <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
        <button type="submit">zapisz zmiany</button>
        <a href="{{ route('sesja.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection