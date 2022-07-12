@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <h1>Przedmioty</h1>
@endsection

@section('main-content')
   <?php  echo $subjectTable;  ?>
@endsection