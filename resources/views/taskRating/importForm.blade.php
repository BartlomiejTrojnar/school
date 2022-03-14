<tr data-import-id="{{$importId}}">
  <td colspan="8">
    Dla ucznia <strong style="color: orange;">{{$studentId}} {{$row->imie}} {{$row->nazwisko}}</strong>
    nie znaleziono oceny zadania {{$taskName}} (wersja {{$row->wersja}}).
  </td>
  <td colspan="3" class="c">
    <form action="{{ route('taskRatingImport.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <div class="hidden">
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
      <button class="btn btn-primary import" type="submit" data-import-id="{{$importId}}">importuj</button>
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
  <td>nowa ocena</td>
</tr>