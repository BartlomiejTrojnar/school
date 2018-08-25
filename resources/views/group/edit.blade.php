@extends('layouts.app')
@section('header')
  <h1>Zamiana danych grupy</h1>
@endsection

@section('main-content')
  <form action="{{ route('grupa.update', $group->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="subject_id">przedmiot</label></th>
        <td><?php  print_r($subjectSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_start">data początkowa</label></th>
        <td><input type="date" name="date_start" value="{{$group->date_start}}" required /></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="date" name="date_end" value="{{$group->date_end}}" required /></td>
      </tr>
      <tr>
        <th><label for="comments">uwagi</label></th>
        <td><input type="text" name="comments" value="{{$group->comments}}" size="10" maxlength="30" /></td>
      </tr>
      <tr>
        <th><label for="level">poziom</label></th>
        <td><?php  print_r($levelSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="hours">godziny</label></th>
        <td><input type="number" name="hours" value="{{$group->hours}}" size="1" maxlength="1" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('grupa.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection