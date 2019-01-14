@extends('layouts.app')

@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('js/grade.js') }}"></script>
@endsection

@section('header')
  <h1>Klasy</h1>
@endsection

@section('main-content')
  <?php
    echo $gradeTable;
  ?>
@endsection