<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
@extends('layouts.app')

@section('header')
   <h1>Modyfikacja informacji o roku szkolnym</h1>
@endsection

@section('main-content')
   <form action="{{ route('rok_szkolny.update', $schoolYear->id) }}" method="post" role="form">
   {{ csrf_field() }}
   {{ method_field('PATCH') }}
      <table>
         <tr>
            <th><label for="dateStart">data rozpoczęcia</label></th>
            <td><input type="date" name="dateStart" value="{{ $schoolYear->date_start }}" required /></td>
         </tr>
         <tr>
            <th><label for="dateEnd">data zakończenia</label></th>
            <td><input type="date" name="dateEnd" value="{{ $schoolYear->date_end }}" required /></td>
         </tr>
         <tr>
            <th><label for="date_of_classification_of_the_last_grade">data klasyfikacji ostatnich klas</label></th>
            <td><input type="date" name="date_of_classification_of_the_last_grade" value="{{ $schoolYear->date_of_classification_of_the_last_grade }}" /></td>
         </tr>
         <tr>
            <th><label for="date_of_graduation_of_the_last_grade">data zakończenia nauki ostatnich klas</label></th>
            <td><input type="date" name="date_of_graduation_of_the_last_grade" value="{{ $schoolYear->date_of_graduation_of_the_last_grade }}" /></td>
         </tr>
         <tr>
            <th><label for="date_of_classification">data klasyfikacji</label></th>
            <td><input type="date" name="date_of_classification" value="{{ $schoolYear->date_of_classification }}" /></td>
         </tr>
         <tr>
            <th><label for="date_of_graduation">data zakończenia nauki</label></th>
            <td><input type="date" name="date_of_graduation" value="{{ $schoolYear->date_of_graduation }}" /></td>
         </tr>   
         <tr class="submit"><td colspan="2">
            <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
            <button class="btn btn-primary" type="submit">zapisz zmiany</button>
            <a class="btn btn-primary" href="{{ route('rok_szkolny.index') }}">anuluj</a>
         </td></tr>
      </table>
   </form>
@endsection