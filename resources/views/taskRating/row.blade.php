<tr data-task_rating_id="{{ $taskRating->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 20.02.2023 *********************** -->
   <td><a href="{{ route('ocena_zadania.show', $taskRating->id) }}">{{ $lp }}</a></td>
   @if($version != "forTask") <td style="text-align: left;"><a href="{{ route('zadanie.show', $taskRating->task_id) }}">{{ $taskRating->task->name }}</a></td>   @endif
   @if($version != "forStudent")
      <td style="text-align: left;"><a href="{{ route('uczen.show', $taskRating->student_id) }}">{{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}</a></td>
   @endif
   <td>{{ $taskRating->version }}</td>
   <td>{{ $taskRating->importance }}</td>
   <td>{{ substr($taskRating->deadline, 0, 10) }}</td>
   <td>{{ substr($taskRating->implementation_date, 0, 10) }}</td>
   <td>{{ substr($taskRating->rating_date, 0, 10) }}</td>
   <td>{{ $taskRating->points }} ({{ number_format($taskRating->points/$taskRating->task->points*100, 1) }}%)</td>
   <td>{{ $taskRating->rating }}</td>
   <td>{{ $taskRating->comments }}</tdclass=>
   <td class="diary">
      @if($taskRating->diary)
         <button class="btn-warning entry-diary" data-task_rating_id="{{ $taskRating->id }}"><i class="fa fa-check-circle"></i></button>
      @else
         <button class="btn-warning no-diary"    data-task_rating_id="{{ $taskRating->id }}"><i class="fa fa-circle-o"></i></button>
      @endif
   </td>
   <td class="entry_date">{{ substr($taskRating->entry_date, 0, 10) }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="improvement edit destroy">
      <button class="improvement btn btn-primary"  data-task_rating_id="{{ $taskRating->id }}" title="poprawa"><i class="fa fa-clone"></i></button>
      <button class="edit btn btn-primary"         data-task_rating_id="{{ $taskRating->id }}" title="edytuj"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"      data-task_rating_id="{{ $taskRating->id }}" title="usuń"><i class="fa fa-remove"></i></button>
   </td>
</tr>