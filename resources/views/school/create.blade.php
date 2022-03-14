<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
@extends('layouts.app')

@section('header')
   <h1>Dodawanie szkoły</h1>
@endsection

@section('main-content')
   <form action="{{ route('szkola.store') }}" method="post" role="form">
   {{ csrf_field() }}
      <table>
         <tr>
            <th><label for="name">nazwa</label></th>
            <td><input type="text" name="name" size="40" autofocus required /></td>
         </tr>
         <tr>
            <th><label for="id_OKE">identyfikator OKE</label></th>
            <td><input type="text" name="id_OKE" /></td>
         </tr>   
         <tr class="submit"><td colspan="2">
            <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
            <button class="btn btn-primary" type="submit">dodaj</button>
            <a class="btn btn-primary" href="{{ route('szkola.index') }}">anuluj</a>
         </td></tr>
      </table>
   </form>
@endsection