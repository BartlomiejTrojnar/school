@extends('layouts.app')
@section('header')
  <h1>Dodawanie oceny zadania</h1>
@endsection

@section('main-content')
  <form action="{{ route('ocena_zadania.storeLot') }}" method="post" role="form">
  {{ csrf_field() }}
    <table>
      <tr>
        <th><label for="task_id">zadanie</label></th>
        <td colspan="4">
          {{ $task->name }}, waga: {{ $task->importance }}
          <input type="hidden" name="task_id" value="{{$task->id}}" />
        </td>
      </tr>
      <tr>
        <th><label for="deadline">termin</label></th>
        <td colspan="4"><input type="date" name="deadline" required value="{{ session()->get('dateView') }}" /></td>
      </tr>
      <tr>
        <th><label for="importance">waga</label></th>
        <td colspan="4"><input type="text" name="importance" size="3" maxlength="4" required value="{{$task->importance}}" /></td>
      </tr>
      <tr>
        <th><label for="student_id">uczeń</label></th>
        <td><ol>
            <?php $count=0; ?>
            @foreach($students as $student)
              <?php $count++ ?>
              @if($student->countTaskRatings)
                <li>
                  <input type="checkbox" name="student{{$student->id}}" value="{{$student->id}}" /></input>
                  {{ $student->first_name }} {{ $student->last_name }}
                </li>
              @else
                <li style="background: #393;">
                  <input type="checkbox" name="student{{$student->id}}" value="{{$student->id}}" checked /></input>
                  {{ $student->first_name }} {{ $student->last_name }}
                </li>
              @endif
              @if($count % 10 == 0) </ol></td><td><ol> @endif
            @endforeach
        </ol></td>
      </tr>

      <tr class="submit"><td colspan="5">
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary" type="submit">dodaj</button>
          <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
@endsection