@extends('layouts.app')
@section('header')
  <h1>zmiana danych przedmiotu</h1>
@endsection

@section('main-content')
  <form action="{{ route('przedmiot.update', $subject->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="name">nazwa</label></th>
        <td><input type="text" name="name" value="{{ $subject->name }}" size="30" maxlength="60" required /></td>
      </tr>
      <tr>
        <th><label for="short_name">skrót</label></th>
        <td><input type="text" name="short_name" value="{{ $subject->short_name }}" size="12" maxlength="15" /></td>
      </tr>
      <tr>
        <th><label for="actual">aktualny</label></th>
        <td><input type="checkbox" name="actual" @if($subject->actual==1) checked="checked" @endif /></td>
      </tr>
      <tr>
        <th><label for="order_in_the_sheet">kolejność w arkuszu</label></th>
        <td><input type="text" name="order_in_the_sheet" value="{{ $subject->order_in_the_sheet }}" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <th><label for="expanded">rozszerzany</label></th>
        <td><input type="checkbox" name="expanded" @if($subject->expanded==1) checked="checked" @endif /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection