@extends('layouts.app')
@section('header')
  <h1>zmiana nazwy wzoru świadectwa</h1>
@endsection
@section('main-content')
  <form action="{{ route('wzor_swiadectwa.update', $pattern->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" value="{{ $pattern->name }}" /></input></td>
      </tr>
      <tr>
        <th><label for="destiny">przeznaczenie</label></th>
        <td><input type="text" name="destiny" value="{{ $pattern->destiny }}" size="30" maxlength="40" /></input></td>
      </tr>

      <tr class="submit">
        <td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('wzor_swiadectwa.index') }}">anuluj</a>
        </td>
      </tr>
    </table>
  </form>
@endsection