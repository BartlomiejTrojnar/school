@extends('layouts.app')
@section('header')
  <h1>dodawanie sali lekcyjnej</h1>
@endsection

@section('main-content')
  <form action="{{ route('sala.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" size="15" maxlength="20" required autofocus /></td>
      </tr>
      <tr>
        <th><label for="capacity">pojemność</label></th>
        <td><input type="number" name="capacity" min="0" max="100" /></td>
      </tr>
      <tr>
        <th><label for="floor">piętro</label></th>
        <td><input type="number" name="floor" min="0" max="2" /></td>
      </tr>
      <tr>
        <th><label for="line">rząd</label></th>
        <td><input type="number" name="line" min="1" max="10" /></td>
      </tr>
      <tr>
        <th><label for="column">kolumna</label></th>
        <td><input type="number" name="column" min="1" max="10" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ route('sala.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection