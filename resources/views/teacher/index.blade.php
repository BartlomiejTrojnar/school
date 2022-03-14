@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('public/js/teacher/index.js') }}"></script>
@endsection

@section('header')
   <h1>Nauczyciele</h1>
@endsection

@section('main-content')
   <?php echo $teacherTable; ?>
   <a class="btn btn-danger" href="{{ route('nauczyciel.printOrder') }}">ustaw kolejność nauczycieli</a>
@endsection