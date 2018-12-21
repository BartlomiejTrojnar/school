@extends('layouts.app')

@section('header')
  <h1>Polecenia</h1>
@endsection

@section('main-content')
  <?php
    echo $commandTable;
  ?>
@endsection