<tr data-teacher_id="{{$teacher->id}}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 29.08.2022 ********************** -->
   <td>{{ $lp }}</td>
   <td>{{ $teacher->degree }}</td>
   <td>{{ $teacher->first_name }}</td>
   <td><a href="{{ route('nauczyciel.show', $teacher->id) }}">{{ $teacher->last_name }}</a></td>
   <td>{{ $teacher->family_name }}</td>
   <td>{{ $teacher->short }}</td>
   <td>{{ $teacher->classroom->name }}</td>
   <td>{{ substr($teacher->first_year->date_start,0,4) }}</td>
   <td>@if($teacher->last_year_id) {{ substr($teacher->last_year->date_end,0,4) }} @endif</td>
   <td>{{ $teacher->order }}</td>
   <td>{{ $teacher->updated_at }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"    data-teacher_id="{{ $teacher->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-teacher_id="{{ $teacher->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>