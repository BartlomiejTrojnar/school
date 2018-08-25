@extends('layouts.app')

@section('header')
  <h1>Deklaracje maturalne</h1>
@endsection

@section('main-content')
  <table id="declarations">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/deklaracja/sortuj/student_id') }}">uczeń</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/session_id') }}">sesja</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/application_number') }}">numer zgłoszenia</a></th>
        <th><a href="{{ url('/deklaracja/sortuj/student_code') }}">kod ucznia</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>

      <tr>
        <td>-</td>
        <td><?php  print_r($studentSelectField);  ?></td>
        <td><?php  print_r($sessionSelectField);  ?></td>
        <td colspan="6">=</td>
      </tr>
    </thead>
    <tbody>

    @foreach($declarations as $declaration)
      <tr>
        <td><a href="{{ route('deklaracja.show', $declaration->id) }}">{{ $declaration->id }}</a></td>
        <td>{{ $declaration->student_id }}</td>
        <td>{{ $declaration->session_id }}</td>
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