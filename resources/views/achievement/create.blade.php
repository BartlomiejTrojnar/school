@extends('layouts.app')
@section('header')
  <h1>Dodawanie osiągnięć</h1>
@endsection

@section('main-content')
  <form action="{{ route('osiagniecie.store') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="certificate_id">świadectwo</label></th>
        <td><?php  print_r($certificateSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="inscription">osiągnięcie</label></th>
        <td><input type="text" name="inscription" size="100" maxlength="200" required autofocus /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">dodaj</button>
          <a href="{{ route('osiagniecie.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection