<tr data-task_id="{{$task->id}}" style="display: none;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->
   <td>{{ $lp }}</td>
   <td><a href="{{ route('zadanie.show', $task->id) }}">{{ $task->name }}</a></td>
   <td>{{ $task->points }}</td>
   <td>{{ $task->importance }}</td>
   <td>{{ $task->sheet_name }}</td>
   <td>{{ count($task->commands) }}</td>
   <td>{{ count($task->taskRatings) }}</td>
   <td class="c small">{{ substr($task->created_at, 0, 10) }}</td>
   <td class="c small">{{ substr($task->updated_at, 0, 10) }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"    data-task_id="{{ $task->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-task_id="{{ $task->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>