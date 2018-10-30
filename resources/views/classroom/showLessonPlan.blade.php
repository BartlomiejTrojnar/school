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

  <h2>Plan lekcji dla sali</h2>
  <table id="lessonPlan">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/plan_lekcji/sortuj/group_id') }}">grupa</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/lesson_plan_id') }}">godzina</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/date_end') }}">data końcowa</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($lessonPlans as $lesson)
      <tr>
        <td><a href="{{ route('lekcja.show', $lesson->id) }}">{{ $lesson->id }}</a></td>
        <td>
          <a href="{{ route('grupa.show', $lesson->group_id) }}">
            {{ $lesson->group_id }}
          </a>
        </td>
        <td>{{ $lesson->lesson_hour_id }}</td>
        <td>{{ $lesson->date_start }}</td>
        <td>{{ $lesson->date_end }}</td>
        <td>{{ $lesson->created_at }}</td>
        <td>{{ $lesson->updated_at }}</td>
        <td><a href="{{ route('plan_lekcji.edit', $lesson->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('plan_lekcji.destroy', $lesson->id) }}" method="post" id="delete-form-{{$lesson->id}}">
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
