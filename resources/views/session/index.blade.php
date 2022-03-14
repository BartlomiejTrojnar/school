<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 27.10.2021 *********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/session/index.js') }}"></script>
@endsection

@section('header')
   <h1>Sesje maturalne</h1>
@endsection

@section('main-content')
   {!! $sessions->render() !!}
   <table id="sessions">
      <thead>
         <tr>
            <th>id</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"rok", "routeName"=>"session.orderBy", "field"=>"year", "sessionVariable"=>"SessionOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"typ", "routeName"=>"session.orderBy", "field"=>"type", "sessionVariable"=>"SessionOrderBy"]);
            ?>
            <th>deklaracje</th>
            <th>egzaminy</th>
            <th>opis<br />egzaminów</th>
            <th>zmień / usuń</th>
         </tr>
      </thead>

      <tbody>
         @foreach($sessions as $session)
            <tr data-session_id="{{$session->id}}">
               <td>{{ $session->id }}</td>
               <td><a href="{{ route('sesja.show', $session->id) }}">{{ $session->year }}</a></td>
               <td>{{ $session->type }}</td>
               <td>{{ count($session->declarations) }}</td>
               <td>{{ $session->exams() }}</td>
               <td>{{ count($session->examDescriptions) }}</td>

               <td class="edit destroy c">
                  <button class="edit btn btn-primary"    data-session_id="{{ $session->id }}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-session_id="{{ $session->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach
         <tr class="create"><td colspan="7"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button></td></tr>
      </tbody>
   </table>
@endsection