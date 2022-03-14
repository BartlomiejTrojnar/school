<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
@extends('layouts.app')

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
            <th colspan="2">+/-</th>
         </tr>
      </thead>

      <tbody>
         <?php $count = 0; ?>
         @foreach($schools as $school)
            <?php $count++; ?>
            <tr>
               <td>{{ $count }}</td>
               <td><a href="{{ route('szkola.show', $school->id) }}">{{ $school->name }}</a></td>
               <td>{{ $school->id_OKE }}</td>

               <td class="edit destroy">
                  <a style="display: inline;" class="btn btn-primary" href="{{ route('szkola.edit', $school->id) }}"><i class="fa fa-edit"></i></a>
                  <form style="display: inline;" action="{{ route('szkola.destroy', $school->id) }}" method="post" id="delete-form-{{$school->id}}">
                     {{ csrf_field() }}
                     {{ method_field('DELETE') }}
                     <button class="btn btn-primary"><i class='fa fa-minus'></i></button>
                  </form>
               </td>
            </tr>
         @endforeach
         <tr class="create"><td colspan="5">
               <a class="btn btn-primary" href="{{ route('szkola.create') }}"><i class='fa fa-plus'></i></a>
         </td></tr>
      </tbody>
   </table>
@endsection