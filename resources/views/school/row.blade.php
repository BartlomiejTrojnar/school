<tr data-school_id="{{ $school->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 30.12.2021 ********************** -->
   <td>{{ $lp }}</td>
   <td><a href="{{ route('szkola.show', $school->id.'/info') }}">{{ $school->name }}</a></td>
   <td>{{ $school->id_OKE }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary"      data-school_id="{{ $school->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary"   data-school_id="{{ $school->id }}"><i class="fa fa-remove"></i></button>
   </td>
</tr>