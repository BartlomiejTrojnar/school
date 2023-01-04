<tr data-subject_id="{{ $subject->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.01.2023 ********************** -->
   <td>{{ $lp }}</td>
   <td><a href="{{ route('przedmiot.show', $subject->id) }}">{{ $subject->name }}</a></td>
   <td>{{ $subject->short_name }}</a></td>
   <td>@if( $subject->actual ) <i class="fa fa-check"></i> @endif</td>
   <td>{{ $subject->order_in_the_sheet }}</td>
   <td>@if( $subject->expanded ) <i class="fa fa-check"></i> @endif</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"    data-subject_id="{{ $subject->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-subject_id="{{ $subject->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>