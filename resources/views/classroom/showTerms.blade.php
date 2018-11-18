@extends('layouts.app')

@section('header')
  <h1>{{ $classroom->name }}</h1>
  <aside id="strzalka_l">
    <a href="{{ route('sala.show', $previous) }}">
      <img src="{{ asset('css/strzalka_l1.png') }}" alt="poprzednia">
    </a>
  </aside>
  <aside id="strzalka_p">
    <a href="{{ route('sala.show', $next) }}">
      <img src="{{ asset('css/strzalka_p1.png') }}" alt="nastepna">
    </a>
  </aside>
@endsection

@section('main-content')
  <ul class="nav nav-tabs nav-justified">
    <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showLessonPlan') }}">plan lekcji</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showTerms') }}">terminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('sala/'.$classroom->id.'/showExams') }}">egzaminy</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('sala.index') }}">powrót</a></li>
  </ul>

  <h2>Terminy egzaminów w sali</h2>
  <table id="terms">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/termin/sortuj/exam_description_id') }}">opis egzaminu</a></th>
        <th><a href="{{ url('/termin/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/termin/sortuj/date_end') }}">data końcowa</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($terms as $term)
      <tr>
        <td><a href="{{ route('termin.show', $term->id) }}">{{ $term->id }}</a></td>
        <td>
          <a href="{{ route('opis_egzaminu.show', $term->exam_description_id) }}">
            {{ $term->exam_description_id }}
          </a>
        </td>
        <td>{{ $term->date_start }}</td>
        <td>{{ $term->date_end }}</td>
        <td>{{ $term->created_at }}</td>
        <td>{{ $term->updated_at }}</td>
        <td><a href="{{ route('termin.edit', $term->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('termin.destroy', $term->id) }}" method="post" id="delete-form-{{$term->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="11"><a href="{{ route('termin.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection
