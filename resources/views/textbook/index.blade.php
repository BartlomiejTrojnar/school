@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/textbook/index.js') }}"></script>
@endsection

@section('header')
   <h1>Podręczniki</h1>
@endsection

@section('main-content')
   <ul class="nav nav-tabs nav-justified">
      <li class="nav-item"><a class="nav-link" href="{{ route('podrecznik.index') }}">spis podręczników <i class='fa fa-undo'></i></a></li>
      <li class="nav-item"><a class="nav-link" href="{{ route('wybor_podrecznika.index') }}">wybory podręczników <i class='fa fa-undo'></i></a></li>
   </ul>

   <?php  echo $textbookTable;  ?>
@endsection