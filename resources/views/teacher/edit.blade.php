<tr class="editRow" data-teacher_id="{{ $teacher->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
   <form action="{{ route('nauczyciel.update', $teacher->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td><var class="lp">{{ $lp }}</var></td>
      <td>
         <input type="hidden" name="id" value="{{ $teacher->id }}" />
         <input type="text" name="degree" value="{{ $teacher->degree }}" size="8" maxlength="10" />
      </td>
      <td><input type="text" name="first_name" value="{{ $teacher->first_name }}" size="12" maxlength="16" require /></td>
      <td><input type="text" name="last_name" value="{{ $teacher->last_name }}" size="12" maxlength="18" required /></td>
      <td><input type="text" name="family_name" value="{{ $teacher->family_name }}" size="12" maxlength="15" /></td>
      <td><input type="text" name="short" value="{{ $teacher->short }}" size="2" maxlength="2" /></td>
      <td><?php  print_r($classroomSF);  ?></td>
      <td><?php  print_r($firstYearSF);  ?></td>
      <td><?php  print_r($lastYearSF);  ?></td>
      <td><input type="number" name="order" value="{{ $teacher->order }}" size="2" maxlength="2" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 225px;">
         <button class="update btn btn-primary"       data-teacher_id="{{ $teacher->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-teacher_id="{{ $teacher->id }}">anuluj</button>
      </td>
   </form>
</tr>