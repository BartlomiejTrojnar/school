<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 ********************** -->
<tr data-student_history_id="{{ $studentHistory->id }}">
   <td @if(!$studentHistory->confirmation_date)class="not_confirmation"@endif>{{ $studentHistory->date }}</td>
   <td @if(!$studentHistory->confirmation_event)class="not_confirmation"@endif>{{ $studentHistory->event }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-student_history_id="{{ $studentHistory->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-student_history_id="{{ $studentHistory->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>