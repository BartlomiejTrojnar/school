<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ url('public/js/school/index.js') }}"></script>
@endsection

@section('header')
   <h1>Szkoły</h1>
@endsection

@section('main-content')
<table id="schools">
<thead>
   <tr>
      <th>id</th>
      <?php
         echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"szkola.orderBy", "field"=>"name", "sessionVariable"=>"SchoolOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"identyfikator OKE", "routeName"=>"szkola.orderBy", "field"=>"id_OKE", "sessionVariable"=>"SchoolOrderBy"]);
      ?>
      <th colspan="2">zmień / usuń</th>
   </tr>
</thead>

<tbody>
   <?php $count = 0; ?>
   @foreach($schools as $school)
   <tr data-school_id="{{ $school->id }}">
      <td>{{ ++$count }}</td>
      <td><a href="{{ route('szkola.show', $school->id) }}">{{ $school->name }}</a></td>
      <td>{{ $school->id_OKE }}</td>
      <td class="edit destroy c">
         <button class="edit btn btn-primary"      data-school_id="{{ $school->id }}"><i class="fa fa-edit"></i></button>
         <button class="destroy btn btn-primary"   data-school_id="{{ $school->id }}"><i class="fa fa-remove"></i></button>
      </td>
   </tr>
   @endforeach
   <tr class="create"><td colspan="4"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
</tbody>
</table>
<input type="hidden" id="countSchools" value="{{ count($schools) }}" />
@endsection