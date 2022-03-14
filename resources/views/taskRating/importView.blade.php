@extends('layouts.app')

@section('java-script')
  <script language="javascript" type="text/javascript" src="{{ asset('public/js/taskRating/import.js') }}"></script>
@endsection

@section('main-content')
  <h1>Importowanie ocen zadania</h1>
  <table>
    <tr>
      <th>termin</th>
      <th>data wykonania</th>
      <th>uwagi</th>
      <th>wersja</th>
      <th>waga</th>
      <th>punkty</th>
      <th>ocena</th>
      <th>data oceny</th>
      <th>dziennik</th>
      <th>data dziennika</th>
      <th>Import?</th>
    </tr>

    <?php print $rows ?>
  </table>
@endsection