@extends('layouts.app')

@section('header')
  <h1>Klasy</h1>
@endsection

@section('main-content')
  <?php
    echo $gradeTable;
  ?>
@endsection