<tr data-task_rating_id="{{ $taskRating->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 11.11.2022 *********************** -->
   <td><a href="{{ route('ocena_zadania.show', $taskRating->id) }}">{{ $lp }}</a></td>
   <td><a href="{{ route('zadanie.show', $taskRating->task_id) }}">{{ $taskRating->task->name }}</a></td>
   <td>{{ substr($taskRating->deadline, 0, 10) }}</td>
   <td>{{ $taskRating->implementation_date }}</td>
   <td>{{ $taskRating->version }}</td>
   <td>{{ $taskRating->importance }}</td>
   <td>{{ substr($taskRating->rating_date, 0, 10) }}</td>
   <td>{{ $taskRating->points }} ({{ number_format($taskRating->points/$taskRating->task->points*100, 1) }}%)</td>
   <td>{{ $taskRating->rating }}</td>
   <td>{{ $taskRating->comments }}</tdclass=>
   <td class="diary">
      @if($taskRating->diary)
         <button class="btn-warning entry-diary" data-task_rating_id="{{ $taskRating->id }}"><i class='fas fa-circle'></i></button>
      @else
         <button class="btn-warning no-diary"    data-task_rating_id="{{ $taskRating->id }}"><i class='far fa-circle'></i></button>
      @endif
   </td>
   <td>{{ $taskRating->entry_date }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="improvement edit destroy">
      <button class="improvement btn btn-primary"  data-task_rating_id="{{ $taskRating->id }}" title="poprawa"><i class="fa fa-edit"></i></button>
      <button class="edit btn btn-primary"         data-task_rating_id="{{ $taskRating->id }}" title="edytuj"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"      data-task_rating_id="{{ $taskRating->id }}" title="usuń"><i class="fas fa-remove"></i></button>
   </td>
</tr>