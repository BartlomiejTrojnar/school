<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="module" src="{{ url('public/js/teacher/index.js') }}"></script>
@endsection

@section('header')
   <h1>Nauczyciele</h1>
@endsection

@section('main-content')
   <?php echo $teachersTable; ?>
@endsection