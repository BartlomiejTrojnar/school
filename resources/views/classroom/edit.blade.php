@extends('layouts.app')
@section('header')
  <h1>zmiana danych sali lekcyjnej</h1>
@endsection
@section('main-content')
  <form action="{{ route('sala.update', $classroom->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" value="{{ $classroom->name }}" size="15" maxlength="20" /></td>
      </tr>
      <tr>
        <th><label for="capacity">pojemność</label></th>
        <td><input type="text" name="capacity" value="{{ $classroom->capacity }}" size="2" maxlength="3" /></td>
      </tr>
      <tr>
        <th><label for="floor">piętro</label></th>
        <td><input type="text" name="floor" value="{{ $classroom->floor }}" size="1" maxlength="1" /></td>
      </tr>
      <tr>
        <th><label for="line">rząd</label></th>
        <td><input type="text" name="line" value="{{ $classroom->line }}" size="1" maxlength="2" /></td>
      </tr>
      <tr>
        <th><label for="column">kolumna</label></th>
        <td><input type="text" name="column" value="{{ $classroom->column }}" size="1" maxlength="2" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="{{ route('sala.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection