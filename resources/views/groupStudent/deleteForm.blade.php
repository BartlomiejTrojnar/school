<section id="groupStudentDeleteForm">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 18.02.2022 *********************** -->
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
         <th>uczeń  fsdgfsdgsdfgfd</th>
         <td colspan="2">
            {{ $groupStudent->student->first_name }} {{ $groupStudent->student->last_name }}
            <input type="hidden" name="student_id" value="{{$groupStudent->student_id}}" />
         </td>
      </tr>

      <tr>
         <th>usuń od dnia:</th>
         <td><input type="date" name="end" value="{{ date('Y-m-d', strtotime('-1 day', strtotime( session()->get('dateView') ))) }}" required /></td>
         <td><button id="removeFromDate" class="btn btn-primary" data-group_student_id="{{ $groupStudent->id }}">zapisz</button></td>
      </tr>
   </table>

   <div class="c">
      <button class="btn btn-primary completeRemove" data-group_student_id="{{ $groupStudent->id }}">usuń całkiem</button>   
      <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
   </div>
</section>