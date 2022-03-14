@extends('layouts.app')

@section('header')
  <h1>Sale lekcyjne</h1>
@endsection

@section('main-content')
  <?php
    echo $classroomTable;
  ?>
@endsection