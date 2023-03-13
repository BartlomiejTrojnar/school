@extends('layouts.app')

@section('java-script')
   <script src="{{ asset('public/js/rememberDates.js') }}"></script>
   <script src="{{ asset('public/js/group/operations.js') }}"></script>
@endsection

@section('header')
   <h1>Grupy</h1>
@endsection

@section('main-content')
   <?php echo $groupTable; ?>
   <a class="btn btn-primary" href="{{ route('groupStudent.exportGroups') }}">eksportuj (Excel)</a>
@endsection