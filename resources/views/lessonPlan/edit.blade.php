@extends('layouts.app')
@section('header')
  <h1>Modyfikowanie planu lekcji</h1>
@endsection

@section('main-content')
  <form action="{{ route('plan_lekcji.update', $lessonPlan->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <table>
      <tr>
        <th><label for="group_id">grupa</label></th>
        <td><?php  print_r($groupSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="lesson_hour_id">godzina</label></th>
        <td><?php  print_r($lessonHourSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="classroom_id">sala</label></th>
        <td><?php  print_r($classroomSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_start">data początkowa</label></th>
        <td><input type="date" name="date_start" value="{{$lessonPlan->date_start}}" required /></td>
      </tr>
      <tr>
        <th><label for="date_end">data końcowa</label></th>
        <td><input type="date" name="date_end" value="{{$lessonPlan->date_end}}" required /></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button type="submit">zapisz zmiany</button>
          <a href="{{ route('plan_lekcji.index') }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection