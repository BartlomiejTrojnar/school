@extends('layouts.app')
@section('header')
  <h1>Importowanie poleceń do zadania</h1>
@endsection

@section('main-content')
<form action="{{ route('polecenie.storeFromImport') }}" method="post" role="form">
  {{ csrf_field() }}
  <table>
    <tr><th>---</th><th>import/zmiana?</th><th>numer<br/>polecenia</th><th>polecenie</th><th>opis</th><th>punkty</th></tr>

    <?php echo $forms; ?>

    <tr class="submit"><td colspan="6">
      <input type="hidden" name="countImportCommands" value="{{ $countImportCommands }}" />
      <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
      <button class="btn btn-primary" type="submit">importuj</button>
      <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
    </td></tr>
  </table>
</form>
@endsection