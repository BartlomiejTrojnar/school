@extends('layouts.app')

@section('title')
   <title>Import numerów uczniów</title>
@endsection

@section('header')
   Import numerów uczniów
@endsection

@section('main-content')
   <h1>Zaimportowano:</h1>
   <?php echo $result; ?>
@endsection