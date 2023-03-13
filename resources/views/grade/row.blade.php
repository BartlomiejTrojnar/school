<tr data-grade_id="{{ $grade->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 17.01.2023 ********************** -->
   <td>{{ $lp }}</td>
   @if($year)
      <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $year - $grade->year_of_beginning }}{{ $grade->symbol }}</a></td>
   @else
      <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</a></td>
   @endif
   <td><a href="{{ route('szkola.show', $grade->school_id) }}">{{ $grade->school->name }}</a></td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"    data-grade_id="{{ $grade->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-grade_id="{{ $grade->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>