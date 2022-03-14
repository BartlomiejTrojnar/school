@extends('layouts.app')

@section('header')
  <h1>Przedmioty</h1>
@endsection

@section('main-content')
  <?php
    echo $subjectTable;
  ?>
@endsection