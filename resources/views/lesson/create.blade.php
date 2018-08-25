@extends('layouts.app')
@section('header')
  <h1>Dodawanie lekcji</h1>
@endsection

@section('main-content')
  <form action="{{ route('lekcja.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="group_id">grupa</label></th>
        <td><?php  print_r($groupSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="teacher_id">nauczyciel</label></th>
        <td><?php  print_r($teacherSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="lesson_date">data lekcji</label></th>
        <td><input type="date" name="lesson_date" min="2018-09-03" required /></td>
      </tr>
      <tr>
        <th><label for="lesson_length">długość lekcji</label></th>
        <td><input type="number" name="lesson_length" min="1" max="45" required /></td>
      </tr>
      <tr>
        <th><label for="number">numer</label></th>
        <td><input type="number" name="number" min="1" /></td>
      </tr>
      <tr>
        <th><label for="topic_entered">temat wpisany</label></th>
        <td><input type="text" name="topic_entered" size="25" maxlength="45" /></td>
      </tr>
      <tr>
        <th><label for="topic_realized">temat zrealizowany</label></th>
        <td><input type="text" name="topic_realized" size="25" maxlength="45" /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" size="25" maxlength="45" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('lekcja.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection