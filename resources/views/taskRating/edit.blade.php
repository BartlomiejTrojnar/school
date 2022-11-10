<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
<tr class="editRow" data-task_rating_id="{{ $taskRating->id }}">
   <form action="{{ route('ocena_zadania.update', $taskRating->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      
      <td colspan="2"><?php  print_r($taskSF);  ?></td>
      <td><input type="date" name="deadline"             value="{{ substr($taskRating->deadline, 0, 10) }}" required /></td>
      <td><input type="date" name="implementation_date"  value="{{ substr($taskRating->implementation_date, 0, 10) }}" /></td>
      <td><input type="number" name="version" value="{{ $taskRating->version }}" size="2" maxlength="1" required /></td>
      <td><input type="text" name="importance" value="{{ $taskRating->importance }}" size="3" maxlength="4" required /></td>
      <?php /*
      @if($version=="forIndex" || $version=="forGrade")  <td></td>   @endif
      @if($version=="forStudent")   <td><input type="hidden" name="student_id" value="{{$declaration->student_id}}" /></td> @endif
      @if($version=="forSession")   <td><input type="hidden" name="session_id" value="{{$declaration->session_id}}" /></td> @endif

      @if($version!="forStudent")   <td class="c"><?php  print_r($student);  ?></td>   @endif
      @if($version!="forSession")   <td><?php  print_r($session);  ?></td> @endif
      <td class="c"><input type="number" name="application_number" min="1" max="10" size="2" required value="{{$declaration->application_number}}" /></td>
      <td class="c"><input type="text" name="student_code" size="2" maxlength="3" value="{{$declaration->student_code}}" /></td>
   */ ?>
      <td><input type="date" name="rating_date" value="{{ substr($taskRating->rating_date, 0, 10) }}" /></td>
      <td>
         <input type="text" name="points" value="{{ $taskRating->points }}" size="3" maxlength="4" /> z <span id="maxPoints">{{ $taskRating->task->points }}</span>
         <span id="percent">({{ $taskRating->points/$taskRating->task->points }}%)</span>
      </td>
      <td><input type="text" name="rating"   value="{{ $taskRating->rating }}" size="2" maxlength="2" /></td>
      <td><input type="text" name="comments" value="{{ $taskRating->comments }}" size="20" maxlength="50" /></td>
      <td class="c"><input type="checkbox" name="diary" @if($taskRating->diary) checked @endif /></td>
      <td><input type="date" name="entry_date" value="{{ substr($taskRating->entry_date, 0, 10) }}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="2" class="c" style="width: 250px;">
         <input type="hidden" name="id" value="{{ $taskRating->id }}" />
         <input type="hidden" name="student_id" value="{{ $taskRating->student_id }}" />
         <button class="update btn btn-primary"       data-task_rating_id="{{ $taskRating->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-task_rating_id="{{ $taskRating->id }}">anuluj</button>
      </td>
   </form>
</tr>