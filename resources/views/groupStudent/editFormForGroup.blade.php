<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 19.02.2022 *********************** -->
<aside id="studentEditForm" style="background: brown; padding: 10px; position: absolute;">
  <form action="{{ route('grupa_uczniowie.update', $groupStudent->id) }}" method="post" role="form">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    <table>
      <tr>
        <th>grupa</th>
        <td colspan="2">
          {{ $groupStudent->group->subject->name }}
          {{ $groupStudent->group->comments }}
          @foreach($groupStudent->group->teachers as $groupTeacher)
            ({{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }})
          @endforeach
          <input type="hidden" name="group_id" value="{{$groupStudent->group_id}}" />
        </td>
      </tr>

      <tr>
        <th>uczeń</th>
        <td colspan="2">
          {{ $groupStudent->student->first_name }} {{ $groupStudent->student->last_name }}
          <input type="hidden" name="student_id" value="{{$groupStudent->student_id}}" />
        </td>
      </tr>

      <tr>
        <th><label for="start">data początkowa</label></th>
        <td><input id="start" type="date" name="start" value="{{$groupStudent->start}}" required /></td>
        <td id="proposedDateStart">
          <ul style="list-style: none; padding: 0; margin: 0;">
            <li><span class="proposedDate">{{ $groupStudent->group->start }}</span> <label>data początkowa grupy</label></li>
            @foreach($groupStudent->student->grades as $studentGrade)
              @if( $studentGrade->start >= $groupStudent->group->start && $studentGrade->start <= $groupStudent->group->end )
                <li><span class="proposedDate">{{ $studentGrade->start }}</span> <label>data początkowa ucznia w klasie</label></li>
              @endif
            @endforeach
            @if( session()->get('dateView') >= $groupStudent->group->start &&  session()->get('dateView') <= $groupStudent->group->end )
              <li><span class="proposedDate">{{ session()->get('dateView') }}</span> <label>data widoku</label></li>
            @endif
            @if( date('Y-m-d') >= $groupStudent->group->start && date('Y-m-d') <= $groupStudent->group->end )
              <li><span class="proposedDate">{{ date('Y-m-d') }}</span> <label>data aktualna</label></li>
            @endif
          </ul>
        </td>
      </tr>
    
      <tr>
        <th><label for="end">data końcowa</label></th>
        <td><input id="end" type="date" name="end" value="{{$groupStudent->end}}" required /></td>
        <td id="proposedDateEnd">
          <ul style="list-style: none; padding: 0; margin: 0;">
            <li><span class="proposedDate">{{ $groupStudent->group->end }}</span> <label>data końcowa grupy</label></li>
            @foreach($groupStudent->student->grades as $studentGrade)
              @if( $studentGrade->end >= $groupStudent->group->start && $studentGrade->end <= $groupStudent->group->end )
                <li><span class="proposedDate">{{ $studentGrade->end }}</span> <label>data końcowa ucznia w klasie</label></li>
              @endif
            @endforeach

            <?php $wczoraj = date('Y-m-d', strtotime('-1 day', strtotime( session()->get('dateView') ))); ?>
            @if( session()->get('dateView') > $groupStudent->group->start &&  session()->get('dateView') <= $groupStudent->group->end )
              <li><span class="proposedDate">{{ $wczoraj }}</span> <label>data widoku - 1 dzień</label></li>  
            @endif

            @if( date('Y-m-d') > $groupStudent->group->start && date('Y-m-d') <= $groupStudent->group->end )
              <li><span class="proposedDate">{{ date('Y-m-d') }}</span> <label>data aktualna - 1 dzień</label></li>
            @endif
          </ul>
        </td>
      </tr>

      <tr class="submit"><td colspan="3">
          <input type="hidden" name="validate" value="1" />
          <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
          <button class="btn btn-primary update" data-student_group_id="{{ $groupStudent->id }}" type="submit">zapisz zmiany</button>
          <a class="btn btn-primary cancelUpdate" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
      </td></tr>
    </table>
  </form>
  <div id="error" class="alert alert-info hide"></div>
</aside>