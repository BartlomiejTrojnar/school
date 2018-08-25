@extends('layouts.app')
@section('header')
  <h1>Dodawanie oceny polecenia</h1>
@endsection

@section('main-content')
  <form action="{{ route('ocena_polecenia.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="command_id">polecenie</label></th>
        <td><?php  print_r($commandSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="task_rating_id">ocena zadania</label></th>
        <td><?php  print_r($taskRatingSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="points">punkty</label></th>
        <td><input type="number" name="points" min="0" max="20" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('ocena_polecenia.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection