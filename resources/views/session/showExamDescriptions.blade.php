@extends('layouts.app')

@section('header')
  <h1>Sesja maturalna: {{ $session->year }} {{$session->type}}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('sesja.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('sesja.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showExamDescriptions') }}">opisy egzaminów</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showDeclarations') }}">deklaracje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showTerms') }}">terminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('sesja.index') }}">powrót</a></li>
  </ul>

  <h2>Opisy egzaminów</h2>
  <table>
    <tr>
      <th>id</th>
      <th>przedmiot</th>
      <th>typ egzaminu</th>
      <th>poziom</th>
      <th>max punktów</th>
      <th>utworzono</th>
      <th>data aktualizacji</th>
      <th colspan="2">+/-</th>
    </tr>

    @foreach($examDescriptions as $examDescription)
      <tr>
        <td><a href="{{ route('opis_egzaminu.show', $examDescription->id) }}">{{ $examDescription->id }}</a></td>
        <td>{{ $examDescription->subject->name }}</td>
        <td>{{ $examDescription->type }}</td>
        <td>{{ $examDescription->level }}</td>
        <td>{{ $examDescription->max_points }}</td>
        <td>{{ $examDescription->created_at }}</td>
        <td>{{ $examDescription->updated_at }}</td>
        <td><a href="{{ route('opis_egzaminu.edit', $examDescription->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('opis_egzaminu.destroy', $examDescription->id) }}" method="post" id="delete-form-{{$examDescription->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </td>
    @endforeach

    <tr class="create"><td colspan="9">
      <a href="{{ route('opis_egzaminu.create') }}">
        <img class="create" src="{{ asset('css/plus.png') }}" /> dodaj opis egzaminu
      </a>
    </td></tr>
  </table>
@endsection
