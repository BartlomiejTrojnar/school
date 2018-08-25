@extends('layouts.app')
@section('header')
  <h1>Modyfikacja informacji o nauczycielu w grupie</h1>
@endsection

@section('main-content')
  <form action="{{ route('grupa_nauczyciele.update', $groupTeacher->id) }}" method="post" role="form">
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
        <th><label for="date_start">data początkowa</label></th>
        <td><input type="date" name="date_start" value="{{$groupTeacher->date_start}}" required /></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="date" name="date_end" value="{{$groupTeacher->date_end}}" required /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('grupa_nauczyciele.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection