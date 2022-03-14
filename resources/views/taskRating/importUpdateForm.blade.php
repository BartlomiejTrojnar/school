<tr data-import-id="{{$importId}}" style="background: orange;" class="c">
  <td colspan="6">
    Dla ucznia <strong>{{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}</strong>
    w zadaniu <strong>{{ $taskRating->task->name }}</strong> znaleziono różnice:
  </td>

  <td colspan="3">
    <form action="{{ route('taskRatingImport.store') }}" method="post" role="form">
      <div class="hidden">
        {{ csrf_field() }}
        <input type="date" name="deadline" value="{{substr($row->termin_zadania, 0, 10)}}" />
        <input type="date" name="implementation_date" value="{{substr($row->data_wykonania, 0, 10)}}" />
        <input type="text" name="comments" size="20" maxlength="50" value="{{$row->uwagi}}" />
        <input type="number" name="version" size="1" maxlength="1" value="{{$row->wersja}}" />
        <input type="text" name="importance" size="3" maxlength="4" value="{{$row->waga}}"  />
        <input type="text" name="points" size="3" maxlength="4" value="{{$row->punkty}}" />
        <input type="text" name="rating" size="2" maxlength="2" value="{{$row->ocena}}" />
        <input type="date" name="rating_date" value="{{substr($row->data_oceny, 0, 10)}}" />
        <input type="text" name="diary" @if($row->data_dziennika) value="1" @else value="0" @endif />
        <input type="date" name="entry_date" value="{{substr($row->data_dziennika, 0, 10)}}" />
        <input type="number" name="student_id" value="{{$studentId}}" size="5">
        <input type="number" name="task_id" value="{{$taskId}}" size="3">
      </div>
      <button class="btn btn-primary import" type="submit" data-import-id="{{$importId}}">Dodaj jako nową ocenę</button>
    </form>
  </td>

  <td colspan="2">
    <form action="{{ route('taskRatingImport.update') }}" method="post" role="form">
      <div class="hidden">
        {{ csrf_field() }}
        <input type="date" name="deadline" value="{{substr($row->termin_zadania, 0, 10)}}" />
        <input type="date" name="implementation_date" value="{{substr($row->data_wykonania, 0, 10)}}" />
        <input type="text" name="comments" size="20" maxlength="50" value="{{$row->uwagi}}" />
        <input type="number" name="version" size="1" maxlength="1" value="{{$row->wersja}}" />
        <input type="text" name="importance" size="3" maxlength="4" value="{{$row->waga}}"  />
        <input type="text" name="points" size="3" maxlength="4" value="{{$row->punkty}}" />
        <input type="text" name="rating" size="2" maxlength="2" value="{{$row->ocena}}" />
        <input type="date" name="rating_date" value="{{substr($row->data_oceny, 0, 10)}}" />
        <input type="text" name="diary" @if($row->data_dziennika) value="1" @else value="0" @endif />
        <input type="date" name="entry_date" value="{{substr($row->data_dziennika, 0, 10)}}" />
        <input type="number" name="student_id" value="{{$taskRating->student_id}}" size="5">
        <input type="number" name="task_id" value="{{$taskRating->task_id}}" size="3">
        <input type="number" name="id" value="{{$taskRating->id}}" size="3">
      </div>
      <button class="btn btn-primary update" type="submit" data-import-id="{{$importId}}">Aktualizuj</button>
    </form>
  </td>
</tr>

<tr data-import-id="{{$importId}}" class="c">
  <td>{{substr($row->termin_zadania, 0, 10)}}</td>
  <td>{{substr($row->data_wykonania, 0, 10)}}</td>
  <td>{{$row->uwagi}}</td>
  <td>{{$row->wersja}}</td>
  <td>{{$row->waga}}</td>
  <td>{{$row->punkty}}</td>
  <td>{{$row->ocena}}</td>
  <td>{{substr($row->data_oceny, 0, 10)}}</td>
  <td>@if($row->data_dziennika) <i class="fas fa-circle"></i> @else <i class="far fa-circle"></i> @endif</td>
  <td>{{substr($row->data_dziennika, 0, 10)}}</td>
  <td>(w pliku)</td>
</tr>

<tr data-import-id="{{$importId}}" class="c">
  <td>{{substr($taskRating->deadline, 0, 10)}}</td>
  <td>{{substr($taskRating->implementation_date, 0, 10)}}</td>
  <td>{{$taskRating->comments}}</td>
  <td>{{$taskRating->version}}</td>
  <td>{{$taskRating->importance}}</td>
  <td>{{$taskRating->points}}</td>
  <td>{{$taskRating->rating}}</td>
  <td>{{substr($taskRating->rating_date, 0, 10)}}</td>
  <td>@if($taskRating->diary) <i class="fas fa-circle"></i> @else <i class="far fa-circle"></i> @endif </td>
  <td>{{substr($taskRating->entry_date, 0, 10)}}</td>
  
  <td>(w bazie)</td>
</tr>