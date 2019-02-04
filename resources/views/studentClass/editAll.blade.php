@extends('layouts.app')
@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('js/studentClassEditAll.js') }}"></script>
@endsection

@section('header')
  <h1>Modyfikacja przynależności uczniów do klasy</h1>
@endsection

@section('main-content')
<form action="{{ route('klasy_ucznia.updateAll') }}" method="get" role="form">
  <table>
    <tr>
      <th>uczeń</th>
      <th>klasa</th>
      <th>data od</th>
      <th>data do</th>
      <th>numer</th>
      <th>uwagi</th>
    </tr>

    <tr>
      <th colspan="2">do wpisania</th>
      <td class="c">
        <input id="propositionDateStart" type="date" />
        <input id="confirmAllDateStart" type="checkbox" /><br />
        <button id="enterDateStart" class="btn btn-warning">wpisz</button>
      </td>
      <td class="c">
        <input id="propositionDateEnd" type="date" />
        <input id="confirmAllDateEnd" type="checkbox" /><br />
        <button id="enterDateEnd" class="btn btn-warning">wpisz</button>
      </td>
      <td class="c">
        <input id="confirmAllNumber" type="checkbox" />
      </td>
      <td class="c">
        <input id="propositionComments" type="text" size="25" />
        <input id="confirmAllComments" type="checkbox" /><br />
        <button id="enterComments" class="btn btn-warning">wpisz</button>
      </td>
    </tr>

    @foreach($studentClasses as $sc)
      @if($sc->date_start >= $date_start && $sc->date_end <= $date_end )
      <tr>
        <td>{{ $sc->student->first_name }} {{ $sc->student->last_name }}</td>
        <td>{{ $sc->grade->year_of_beginning }}-{{ $sc->grade->year_of_graduation }} {{ $sc->grade->symbol }}</td>
        <td>
          <input type="date" name="date_start{{$sc->id}}" value="{{$sc->date_start}}" size="10" @if($sc->confirmation_date_start) readonly="readonly" @endif />
          <input class="confirmationDateStart" type="checkbox" name="confirmation_date_start{{$sc->id}}" @if($sc->confirmation_date_start) checked="checked" @endif />
        </td>
        <td>
          <input type="date" name="date_end{{$sc->id}}" value="{{$sc->date_end}}" size="10" @if($sc->confirmation_date_end) readonly="readonly" @endif />
          <input class="confirmationDateEnd" type="checkbox" name="confirmation_date_end{{$sc->id}}" @if($sc->confirmation_date_end) checked="checked" @endif />
        </td>
        <td>
          <input type="text" name="number{{$sc->id}}" value="{{$sc->number}}" size="3" @if($sc->confirmation_numer) readonly="readonly" @endif />
          <input class="confirmationNumber" type="checkbox" name="confirmation_number{{$sc->id}}" @if($sc->confirmation_numer) checked="checked" @endif />
        </td>
        <td>
          <input type="text" name="comments{{$sc->id}}" value="{{$sc->comments}}" size="25" maxlength="32" @if($sc->confirmation_comments) readonly="readonly" @endif />
          <input class="confirmationComments" type="checkbox" name="confirmation_comments{{$sc->id}}" @if($sc->confirmation_comments) checked="checked" @endif />
        </td>
      </tr>
      @endif
    @endforeach

    <tr class="submit"><td colspan="6">
        <input type="hidden" name="historyView" value="{{ $_SERVER['HTTP_REFERER'] }}" />
        <button type="submit" class="btn btn-primary">zapisz zmiany</button>
        <a href="{{ $_SERVER['HTTP_REFERER'] }}" class="btn btn-primary">anuluj</a>
    </tr>
  </table>
</form>
@endsection