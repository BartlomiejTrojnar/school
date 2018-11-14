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


  <h2>Nauczyciele</h2>
  {{ $teachers->links() }}
  <table id="teachers">
    <thead>
      <tr>
        <th><a href="{{ url('/nauczyciel/sortuj/id') }}">id</a></th>
        <th><a href="{{ url('/nauczyciel/sortuj/order') }}">kolejność</a></th>
        <th>
          <a href="{{ url('/nauczyciel/sortuj/first_name') }}">imię</a> i
          <a href="{{ url('/nauczyciel/sortuj/last_name') }}">nazwisko</a>
        </th>
        <th>skrót</th>
        <th><a href="{{ url('/nauczyciel/sortuj/classroom_id') }}">sala</a></th>
        <th>lata pracy:
          <a href="{{ url('/nauczyciel/sortuj/first_year_id') }}">od</a> -
          <a href="{{ url('/nauczyciel/sortuj/last_year_id') }}">do</a>
        </th>
        <th>przedmioty</th>
        <th>klasy</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($teachers as $teacher)
      <tr>
        <td>{{ $teacher->id }}</td>
        <td>{{ $teacher->order }}</td>
        <td>
          <abbr>{{ $teacher->degree }}</abbr>
          <a href="{{ route('nauczyciel.show', $teacher->id) }}">
            {{ $teacher->first_name }} {{ $teacher->last_name }}
          </a>
          @if($teacher->family_name)<small>({{ $teacher->family_name }})</small>@endif
        </td>
        <td>{{ $teacher->short }}</td>
        <td>@if($teacher->classroom_id) {{ $teacher->classroom->name }} @endif</td>
        <td>
          @if($teacher->first_year_id) {{ substr($teacher->first_year->date_start, 0, 4) }}@endif -
          @if($teacher->last_year_id) {{ substr($teacher->last_year->date_end, 0, 4) }} @endif
        </td>
        <td><a href="{{ route('nauczyciel.show', $teacher->id) }}">{{ count($teacher->subjects) }}</a></td>
        <td><dfn>do zrobienia</dfn></td>
        <td>{{ $teacher->updated_at }}</td>
        <td><a href="{{ route('nauczyciel.edit', $teacher->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('nauczyciel.destroy', $teacher->id) }}" method="post" id="delete-form-{{$teacher->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="11"><a href="{{ route('nauczyciel.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection
