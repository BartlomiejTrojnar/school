@extends('layouts.app')
@section('header')
  <h1>Modyfikowanie księgi ucznia</h1>
@endsection

@section('main-content')
  <form action="{{ route('ksiega_uczniow.update', $bookOfStudent->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="school_id">szkoła</label></th>
        <td><?php  print_r($schoolSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><?php  print_r($studentSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="number">numer</label></th>
        <td><input type="number" name="number" min="1" required value="{{$bookOfStudent->number}}" /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="{{ route('ksiega_uczniow.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection