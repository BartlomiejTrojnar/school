<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/classroom/index.js') }}"></script>
@endsection

@section('header')
  <h1>Sale lekcyjne</h1>
@endsection

@section('main-content')
<table id="classrooms">
<thead>
   <tr>
      <th>lp</th>
      <?php
         echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"sala.orderBy", "field"=>"name", "sessionVariable"=>"ClassroomOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"pojemność", "routeName"=>"sala.orderBy", "field"=>"capacity", "sessionVariable"=>"ClassroomOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"piętro", "routeName"=>"sala.orderBy", "field"=>"floor", "sessionVariable"=>"ClassroomOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"rząd", "routeName"=>"sala.orderBy", "field"=>"line", "sessionVariable"=>"ClassroomOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"kolumna", "routeName"=>"sala.orderBy", "field"=>"column", "sessionVariable"=>"ClassroomOrderBy"]);
      ?>
      <th>zmień / usuń</th>
   </tr>
</thead>

<tbody>
   <?php $count = 0; ?>
   @foreach($classrooms as $classroom)
   <tr data-classroom_id="{{ $classroom->id }}">
      <td>{{ ++$count }}</td>
      <td><a href="{{ route('sala.show', $classroom->id) }}">{{ $classroom->name }}</a></td>
      <td>{{ $classroom->capacity }}</td>
      <td>{{ $classroom->floor }}</td>
      <td>{{ $classroom->line }}</td>
      <td>{{ $classroom->column }}</td>
      <td class="edit destroy c">
         <button class="edit btn btn-primary"      data-classroom_id="{{ $classroom->id }}"><i class="fa fa-edit"></i></button>
         <button class="destroy btn btn-primary"   data-classroom_id="{{ $classroom->id }}"><i class="fa fa-remove"></i></button>
      </td>
   </tr>
   @endforeach
   <tr class="create"><td colspan="7"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
</tbody>
</table>
<input type="hidden" id="countClassrooms" value="{{ count($classrooms) }}" />
@endsection