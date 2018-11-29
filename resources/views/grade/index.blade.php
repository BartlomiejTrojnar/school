@extends('layouts.app')

@section('header')
  <h1>Lekcje</h1>
@endsection

@section('main-content')
  <?php
    echo $gradeTable;
  ?>
@endsection