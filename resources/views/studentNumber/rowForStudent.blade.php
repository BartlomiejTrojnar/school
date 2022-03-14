<tr class="c number_row confirmation{{$sn->confirmation_number}}" data-student_number_id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}" style="display: none;">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 05.01.2022 ********************** -->
   <td>
      @if( $yearOfStudy )
         {{ $yearOfStudy - $sn->grade->year_of_beginning }}{{ $sn->grade->symbol }}
      @else
         {{ $sn->grade->year_of_beginning }}-{{ $sn->grade->year_of_graduation }}{{ $sn->grade->symbol }}
      @endif
   </td>

   <td>{{ substr($sn->school_year->date_start, 0, 4) }}/{{ substr($sn->school_year->date_end, 0, 4) }}</td>

   @if($sn->confirmation_number==1)
      <td>
   @else
      <td class="not_confirmation">
   @endif
      <span class="number" data-id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}">{{ $sn->number }}</span>
   </td>

   <td class="destroy edit">
      <button class="edit btn btn-primary"    data-student_number_id="{{ $sn->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-student_number_id="{{ $sn->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>