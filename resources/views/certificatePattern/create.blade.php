@extends('layouts.app')
@section('header')
  <h1>dodawanie wzoru świadectwa</h1>
@endsection

@section('main-content')
  <form action="{{ route('wzor_swiadectwa.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" size="12" maxlength="15" required /></td>
      </tr>
      <tr>
        <th><label for="destiny">przeznaczenie</label></th>
        <td><input type="text" name="destiny" size="30" maxlength="40" /></td>
      </tr>

      <tr class="submit">
        <td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('wzor_swiadectwa.index') }}">anuluj</a>
        </td>
      </tr>
    </table>
  </form>
@endsection