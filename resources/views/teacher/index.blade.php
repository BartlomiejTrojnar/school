<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 30.12.2022 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/teacher/index.js') }}"></script>
@endsection

@section('header')
   <h1>Nauczyciele</h1>
@endsection

@section('main-content')
   <?php echo $teachersTable; ?>
@endsection