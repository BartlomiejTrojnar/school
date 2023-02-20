<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 20.02.2023 *********************** -->
   <form action="{{ route('ocena_zadania.store') }}" method="post" role="form">
   {{ csrf_field() }}
      @if($version=="forStudent")   <td><input type="hidden" name="student_id" value="{{ $student_id }}" /></td>  @endif
      @if($version=="forTask")      <td><input type="hidden" name="task_id" value="{{ $task_id }}" /></td>        @endif
      @if($version!="forStudent")   <td><?php  print_r($studentSF);  ?></td>  @endif
      @if($version!="forTask")      <td><?php  print_r($taskSF);     ?></td>  @endif
      <td><input type="number" name="version" size="2" maxlength="1" min="1" max="9" required /></td>
      <td><input type="text" name="importance" size="3" maxlength="4" required /></td>
      <td><input type="date" name="deadline" required /></td>
      <td><input type="date" name="implementation_date" /></td>
      <td><input type="date" name="rating_date" /></td>
      <td>
         <input type="text" name="points" size="3" maxlength="4" />
         @if($task) z <span id="maxPoints">{{ $task->points }}</span> <span id="percent">(0%)</span>@endif
      </td>
      <td><input type="text" name="rating" size="2" maxlength="2" /></td>
      <td><input type="text" name="comments" size="20" maxlength="50" /></td>
      <td><input type="checkbox" name="diary" /></td>
      <td><input type="date" name="entry_date" /></td>
      <td class="createButtons" colspan="4">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>