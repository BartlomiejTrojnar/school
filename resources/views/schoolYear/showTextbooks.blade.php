@extends('layouts.app')

@section('header')
  <h1>rok szkolny {{ substr($schoolYear->date_start, 0, 4) }}/{{ substr($schoolYear->date_end, 0, 4) }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('rok_szkolny.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('rok_szkolny.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showInfo') }}">informacje</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showClasses') }}">klasy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showStudents') }}">uczniowie</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showTeachers') }}">nauczyciele</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showGroups') }}">grupy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('rok_szkolny/'.$schoolYear->id.'/showTextbooks') }}">podręczniki</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('rok_szkolny.index') }}">powrót</a></li>
  </ul>

  <h2>Podręczniki</h2>
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
    @foreach($textbookChoices as $textbookChoice)
      <tr>
        <td>{{ $textbookChoice->textbook->id }}</td>
        <td><a href="{{ route('przedmiot.show', $textbookChoice->textbook->subject_id) }}">{{ $textbookChoice->textbook->subject->name }}</a></td>
        <td>{{ $textbookChoice->textbook->author }}</td>
        <td><a href="{{ route('podrecznik.show', $textbookChoice->textbook_id) }}">{{ $textbookChoice->textbook->title }}</a></td>
        <td>{{ $textbookChoice->textbook->publishing_house }}</td>
        <td>{{ $textbookChoice->textbook->admission }}</td>
        <td>{{ $textbookChoice->textbook->comments }}</td>
        <td>{{ $textbookChoice->textbook->created_at }}</td>
        <td>{{ $textbookChoice->textbook->updated_at }}</td>
        <td><a href="{{ route('podrecznik.edit', $textbookChoice->textbook_id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('podrecznik.destroy', $textbookChoice->textbook->id) }}" method="post" id="delete-form-{{$textbookChoice->textbook->id}}">
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
