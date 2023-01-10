<tr data-teacher_id="{{ $teacher->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
   <td>{{ $lp }}</td>
   <td>{{ $teacher->degree }}</td>
   <td>{{ $teacher->first_name }}</td>
   <td><a href="{{ route('nauczyciel.show', $teacher->id.'/showSubjects') }}">{{ $teacher->last_name }}</a></td>
   <td>{{ $teacher->family_name }}</td>
   <td>{{ $teacher->short }}</td>
   <td>
      @if($teacher->classroom_id)   <a href="{{ route('sala.show', $teacher->classroom_id) }}">{{ $teacher->classroom->name }}</a> @endif
   </td>
   <td>@if($teacher->first_year_id) {{ substr($teacher->first_year->date_start, 0, 4) }} @endif</td>
   <td>@if($teacher->last_year_id)  {{ substr($teacher->last_year->date_end, 0, 4) }} @endif</td>
   <td>{{ $teacher->order }}</td>
   <td class="updated">{{ substr($teacher->updated_at, 0, 10) }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"      data-teacher_id="{{ $teacher->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"   data-teacher_id="{{ $teacher->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>