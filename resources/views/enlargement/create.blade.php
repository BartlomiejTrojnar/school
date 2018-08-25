@extends('layouts.app')
@section('header')
  <h1>Dodawanie rozszerzenia</h1>
@endsection

@section('main-content')
  <form action="{{ route('rozszerzenie.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><?php  print_r($studentSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="subject_id">przedmiot</label></th>
        <td><?php  print_r($subjectSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="language_level">poziom języka</label></th>
        <td><?php  print_r($levelSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_of_choice">data wyboru</label></th>
        <td><input type="date" name="date_of_choice" required /></td>
      </tr>
      <tr>
        <th><label for="date_of_resignation">data rezygnacji</label></th>
        <td><input type="date" name="date_of_resignation" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('rozszerzenie.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection