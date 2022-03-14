<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
<tr data-grade_id="{{$grade->id}}">
   <td>{{ $lp }}</td>
   @if($year)
      <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $year - $grade->year_of_beginning }}{{ $grade->symbol }}</a></td>
   @else
      <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</a></td>
   @endif
   <td class="edit destroy c">
      <button class="edit btn btn-primary" data-grade_id="{{ $grade->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-grade_id="{{ $grade->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>