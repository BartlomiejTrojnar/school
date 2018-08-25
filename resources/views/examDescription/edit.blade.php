@extends('layouts.app')
@section('header')
  <h1>Modyfikacja opisu egzaminu</h1>
@endsection

@section('main-content')
  <form action="{{ route('opis_egzaminu.update', $examDescription->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="session_id">sesja</label></th>
        <td><?php  print_r($sessionSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="subject_id">przedmiot</label></th>
        <td><?php  print_r($subjectSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="type">typ</label></th>
        <td><?php  print_r($examTypeSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="level">poziom</label></th>
        <td><?php  print_r($levelSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="max_points">max punktów</label></th>
        <td><input type="number" name="max_points" min="1" max="100" value="{{$examDescription->max_points}}" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('opis_egzaminu.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection