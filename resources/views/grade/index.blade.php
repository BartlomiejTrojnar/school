<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; 30.06.2021 ****************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/grade/index.js') }}"></script>
@endsection

@section('header')
   <h1>Klasy</h1>
@endsection

@section('main-content')
   @if( !count($grades) && (empty($_GET['page']) || $_GET['page']>1) )
      <ul class="pagination">
         <li><a id="jumpToThePage" href="{{route('klasa.index', 'page=1')}}">1</a></li>
      </ul>
   @endif

   <?php echo $tableForIndex; ?>
@endsection