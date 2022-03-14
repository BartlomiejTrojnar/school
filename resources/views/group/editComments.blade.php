@extends('layouts.app')

@section('header')
  <h1>modyfikowanie uwag dla grup</h1>
@endsection

@section('main-content')
  <p>Stan na: {{ $dateView }}</p>

  <form action="{{ route('grupa.updateComments') }}" method="post" role="form">
  {{ csrf_field() }}
    <table id="groups">
      <thead>
        <tr>
          <th>id</th>
          <th>klasy</th>
          <th>przedmiot</th>
          <th>poziom</th>
          <th>uwagi</th>
          <th>okres istnienia<br />od - do</th>
          <th>godziny</th>
          <th>nauczyciele</th>
          <th>uczniowie</th>
        </tr>
      </thead>

      <tbody>
        <?php $count = 0; ?>
        @foreach($groups as $group)
          <tr>
            <td>{{ ++$count }}</td>
            <td>
              <?php $studyYear = substr($dateView, 0, 4) - $group->grades[0]->grade->year_of_beginning; ?>
              @if( substr($dateView, 5, 2) > 8 )
                {{ $studyYear+1 }}@foreach($group->grades as $grade){{ $grade->grade->symbol }}@endforeach
              @else
                {{ $studyYear }}@foreach($group->grades as $grade){{ $grade->grade->symbol }}@endforeach
              @endif
            </td>
            <td>{{ $group->subject->name }}</td>
            <td>{{ $group->level }}</td>
            <td>
              <input type="hidden" name="id{{ $group->id }}" value="{{ $group->id }}" />
              <input type="text" name="comments{{ $group->id }}" value="{{ $group->comments }}" />
            </td>
            <td>{{ $group->date_start }} - {{ $group->date_end }}</td>
            <td class="c">{{ $group->hours }}</td>
            <td>
              @foreach($group->teachers as $groupTeacher)
                <small>{{ $groupTeacher->date_start }} {{ $groupTeacher->date_end }}</small>
                {{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}<br />
              @endforeach
            </td>
            <td class="c">{{ count($group->students) }}</td>
          </tr>
        @endforeach

        <tr class="submit"><td colspan="9">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">zapisz zmianki</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
        </td></tr>
      </tbody>
    </table>
  </form>
@endsection