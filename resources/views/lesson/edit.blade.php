@extends('layouts.app')
@section('header')
  <h1>Zamiana danych lekcji</h1>
@endsection

@section('main-content')
  <form action="{{ route('lekcja.update', $lesson->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
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
        <th><label for="date">data lekcji</label></th>
        <td><input type="date" name="date" value="{{$lesson->date}}" min="2018-09-03" required /></td>
      </tr>
      <tr>
        <th><label for="length">długość lekcji</label></th>
        <td><input type="number" name="length" value="{{$lesson->length}}" min="1" max="45" required /></td>
      </tr>
      <tr>
        <th><label for="type">typ lekcji</label></th>
        <td><?php  print_r($lessonTypeSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="number">numer</label></th>
        <td><input type="number" name="number" value="{{$lesson->number}}" min="1" /></td>
      </tr>
      <tr>
        <th><label for="topic_entered">temat wpisany</label></th>
        <td><input type="text" name="topic_entered" value="{{$lesson->topic_entered}}" size="25" maxlength="50" /></td>
      </tr>
      <tr>
        <th><label for="topic_completed">temat zrealizowany</label></th>
        <td><input type="text" name="topic_completed" value="{{$lesson->topic_completed}}" size="25" maxlength="50" /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" value="{{$lesson->comments}}" size="20" maxlength="20" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection