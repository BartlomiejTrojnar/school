<?php $dataBiezaca = date('Y-m-d'); ?>

@extends('layouts.app')

@section('header')
  <h1>Modyfikacja informacji o nauczycielu w grupie</h1>
@endsection
@section('java-script')
  <script src="{{ asset('public/js/group/groupTeacher.js') }}"></script>
@endsection

@section('main-content')
  <div id="error" class="alert alert-info hide"></div>
  <form action="{{ route('grupa_nauczyciele.update', $groupTeacher->id) }}" method="post" role="form">
  {{ csrf_field() }}
  {{ method_field('PATCH') }}
    <input type="hidden" name="group_id" value="{{ $group_id }}" />
    <table>
      <tr>
        <th><label for="teacher_id">nauczyciel</label></th>
        <td><?php  print_r($teacherSelectField);  ?></td>
      </tr>
      <tr>
        <th><label for="date_start">od</label></th>
        <td><input type="date" name="date_start" id="date_start" value="{{ $date_start[0] }}" required /></td>
        <td><button class="btn btn-primary teacherDateStart" id="groupDateStart">{{ $date_start[1] }}</button></td>
        <td><button class="btn btn-primary teacherDateStart">{{ $date_start[2] }}</button></td>
      </tr>
      <tr>
        <th><label for="date_end">do</label></th>
        <td><input type="date" name="date_end" id="date_end" value="{{ $date_end[0] }}" required /></td>
        <td><button class="btn btn-primary teacherDateEnd" id="groupDateEnd">{{ $date_end[1] }}</button></td>
        <td><button class="btn btn-primary teacherDateEnd" id="groupDateEnd">{{ $dataBiezaca }}</button></td>
      </tr>

      <tr class="submit"><td colspan="2">
          <input type="hidden" name="history_view" value="
                 @if($history_view) {{ $history_view }}
                 @else {{ $_SERVER['HTTP_REFERER'] }}
                 @endif
                 " />
          <button class="btn btn-primary" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary" href="@if($history_view) {{ $history_view }} @else {{ $_SERVER['HTTP_REFERER'] }} @endif">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection