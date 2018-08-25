@extends('layouts.app')
@section('header')
  <h1>Zamiana danych klasy w grupie</h1>
@endsection

@section('main-content')
  <form action="{{ route('grupa_klasy.update', $groupClass->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="group_id">grupa</label></th>
        <td><?php  print_r($groupSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="class_id">klasa</label></th>
        <td><?php  print_r($gradeSelectField);  ?></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('grupa_klasy.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection