<tr class="editRow c" data-student_id="{{ $student->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.01.2023 ********************** -->
   <form action="{{ route('uczen.update', $student->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td><var class="lp">{{ $lp }}</var></td>
      <td>
         <input type="hidden" name="id" value="{{ $student->id }}" />
         <input type="text" name="first_name" value="{{ $student->first_name }}" size="10" maxlength="12" required />
      </td>
      <td><input type="text" name="second_name" value="{{ $student->second_name }}" size="10" maxlength="12" /></td>
      <td><input type="text" name="last_name" value="{{ $student->last_name }}" size="12" maxlength="15" required /></td>
      <td><input type="text" name="family_name" value="{{ $student->family_name }}" size="10" maxlength="12" /></td>
      <td><?php  print_r($sexSF);  ?></td>
      <td><input type="text" name="PESEL" value="{{ $student->PESEL }}" size="10" maxlength="11" /></td>
      <td><input type="text" name="place_of_birth" value="{{ $student->place_of_birth }}" size="10" maxlength="12" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="3" style="width: 225px;">
         <button class="update btn btn-primary"       data-student_id="{{ $student->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-student_id="{{ $student->id }}">anuluj</button>
      </td>
   </form>
</tr>