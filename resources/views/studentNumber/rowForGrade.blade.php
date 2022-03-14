<tr class="number_row confirmation{{$sn->confirmation_number}}" data-student_number_id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}" style="display: none;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.12.2021 *********************** -->
   <td class="lp">{{$lp}}</td>
   <td><a href="{{ route('uczen.show', $sn->student_id) }}">
      {{ $sn->student->first_name }} {{ $sn->student->second_name }} {{ $sn->student->last_name }}
   </a></td>
   <td class="c">{{ substr($sn->school_year->date_start, 0, 4) }}/{{ substr($sn->school_year->date_end, 0, 4) }}</td>

   @if($sn->confirmation_number==1)
      <td class="c">
   @else
      <td class="c not_confirmation">
   @endif
      <span class="number" data-id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}">{{ $sn->number }}</span>
   </td>

   <td class="destroy edit c">
      <button class="edit btn btn-primary"    data-student_number_id="{{ $sn->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-student_number_id="{{ $sn->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>