@extends('layouts.app')

@section('header')
  <h1>Plany lekcji</h1>
@endsection

@section('main-content')
  <table id="lessonPlans">
    <thead>
      <tr>
        <th>id</th>
        <th><a href="{{ url('/plan_lekcji/sortuj/group_id') }}">grupa</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/lesson_hour_id') }}">godzina</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/classroom_id') }}">sala</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/date_start') }}">data początkowa</a></th>
        <th><a href="{{ url('/plan_lekcji/sortuj/date_end') }}">data końcowa</a></th>
        <th>wprowadzono</th>
        <th>aktualizacja</th>
        <th colspan="2">+/-</th>
      </tr>
    </thead>
    <tbody>

    @foreach($lessonPlans as $lessonPlan)
      <tr>
        <td><a href="{{ route('plan_lekcji.show', $lessonPlan->id) }}">{{ $lessonPlan->id }}</a></td>
        <td>{{ $lessonPlan->group_id }}</td>
        <td>{{ $lessonPlan->lesson_hour_id }}</td>
        <td>{{ $lessonPlan->classroom_id }}</td>
        <td>{{ $lessonPlan->date_start }}</td>
        <td>{{ $lessonPlan->date_end }}</td>
        <td>{{ $lessonPlan->created_at }}</td>
        <td>{{ $lessonPlan->updated_at }}</td>
        <td><a href="{{ route('plan_lekcji.edit', $lessonPlan->id) }}"><img class="edit" src="{{ asset('css/zmiana.png') }}" alt="[]"></a></td>
        <td>
          <form action="{{ route('plan_lekcji.destroy', $lessonPlan->id) }}" method="post" id="delete-form-{{$lessonPlan->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button><img class="destroy" src="{{ asset('css/minus.png') }}" /></button>
          </form>
        </td>
      </tr>
    @endforeach

      <tr class="create">
        <td colspan="10"><a href="{{ route('plan_lekcji.create') }}"><img class="create" src="{{ asset('css/plus.png') }}" /></a></td>
      </tr>
    </tbody>
  </table>
@endsection