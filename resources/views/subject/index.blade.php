<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 04.01.2023 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="module" src="{{ url('public/js/subject/index.js') }}"></script>
@endsection

@section('header')
   <h1>Przedmioty</h1>
@endsection

@section('main-content')
{!! $subjects->render() !!}

<table id="subjects">
<thead>
   <tr>
      <th>lp</th>
      <?php
         echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"przedmiot.orderBy", "field"=>"name", "sessionVariable"=>"SubjectOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"skrót", "routeName"=>"przedmiot.orderBy", "field"=>"short_name", "sessionVariable"=>"SubjectOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"aktualny?", "routeName"=>"przedmiot.orderBy", "field"=>"actual", "sessionVariable"=>"SubjectOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"kolejność w arkuszu", "routeName"=>"przedmiot.orderBy", "field"=>"order_in_the_sheet", "sessionVariable"=>"SubjectOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"rozszerzany?", "routeName"=>"przedmiot.orderBy", "field"=>"expanded", "sessionVariable"=>"SubjectOrderBy"]);
      ?>
      <th>zmień / usuń</th>
   </tr>
</thead>

<tbody>
   <?php $count = 0; ?>
   @foreach($subjects as $subject)
   <tr data-subject_id={{ $subject->id }}>
      <td>{{ ++$count }}</td>
      <td><a href="{{ route('przedmiot.show', $subject->id) }}">{{ $subject->name }}</a></td>
      <td>{{ $subject->short_name }}</td>
      <td>@if( $subject->actual ) <i class="fa fa-check"></i> @endif</td>
      <td>{{ $subject->order_in_the_sheet }}</td>
      <td>@if( $subject->expanded ) <i class="fa fa-check"></i> @endif</td>
      <td class="edit destroy c">
         <button class="edit btn btn-primary"    data-subject_id="{{ $subject->id }}"><i class="fa fa-edit"></i></button>
         <button class="destroy btn btn-primary" data-subject_id="{{ $subject->id }}"><i class="fa fa-remove"></i></button>
      </td>
   </tr>
   @endforeach
   <tr class="create"><td colspan="7">
      <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
   </td></tr>
</tbody>
</table>
<input type="hidden" id="countSubjects" value="{{ count($subjects) }}" />
@endsection