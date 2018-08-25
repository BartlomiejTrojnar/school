@extends('layouts.app')

@section('header')
  <h1>Podręczniki</h1>
@endsection

@section('main-content')
  <table id="textbooks">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/podrecznik/sortuj/subject_id') }}">przedmiot</a></th>
        <th><a href="{{ url('/podrecznik/sortuj/author') }}">autor</a></th>
        <th><a href="{{ url('/podrecznik/sortuj/title') }}">tytuł</a></th>
        <th><a href="{{ url('/podrecznik/sortuj/publishing_house') }}">wydawnictwo</a></th>
        <th>dopuszczenie</th>
        <th>uwagi</th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>
    @foreach($textbooks as $textbook)
      <tr>
        <td>{{ $textbook->id }}</td>
        <td>{{ $textbook->subject_id }}</td>
        <td>{{ $textbook->author }}</td>
        <td><a href="{{ route('podrecznik.show', $textbook->id) }}">{{ $textbook->title }}</a></td>
        <td>{{ $textbook->publishing_house }}</td>
        <td>{{ $textbook->admission }}</td>
        <td>{{ $textbook->comments }}</td>
        <td>{{ $textbook->created_at }}</td>
        <td>{{ $textbook->updated_at }}</td>
        <td><a href="{{ route('podrecznik.edit', $textbook->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('podrecznik.destroy', $textbook->id) }}" method="post" id="delete-form-{{$textbook->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach
      <tr class="create">
        <td colspan="11"><a href="{{ route('podrecznik.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection