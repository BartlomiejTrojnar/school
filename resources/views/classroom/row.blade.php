<tr data-classroom_id="{{ $classroom->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2021 ********************** -->
   <td>{{ $lp }}</td>
   <td><a href="{{ route('sala.show', $classroom->id.'/info') }}">{{ $classroom->name }}</a></td>
   <td>{{ $classroom->capacity }}</td>
   <td>{{ $classroom->floor }}</td>
   <td>{{ $classroom->line }}</td>
   <td>{{ $classroom->column }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"      data-classroom_id="{{ $classroom->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"   data-classroom_id="{{ $classroom->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>