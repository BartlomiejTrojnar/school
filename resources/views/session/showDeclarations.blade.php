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

  <h2>Deklaracje</h2>
  <table id="declarations">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/deklaracja/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/application_number') }}">numer zgłoszenia</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/student_code') }}">kod ucznia</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>

    <tbody>
    @foreach($declarations as $declaration)
      <tr>
        <td><a href="{{ route('deklaracja.show', $declaration->id) }}">{{ $loop->iteration }}</a></td>
        <td><a href="{{ route('uczen.show', $declaration->student_id) }}">{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</a></td>
        <td>{{ $declaration->application_number }}</td>
        <td>{{ $declaration->student_code }}</td>
        <td>{{ $declaration->created_at }}</td>
        <td>{{ $declaration->updated_at }}</td>
        <td><a href="{{ route('deklaracja.edit', $declaration->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('deklaracja.destroy', $declaration->id) }}" method="post" id="delete-form-{{$declaration->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="9"><a href="{{ route('deklaracja.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection