<tr id="createRow">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 29.08.2022 ********************** -->
   <form action="{{ route('klasa.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="text" name="degree" size="8" maxlength="10" placeholder="mgr" /></td>
      <td><input type="text" name="first_name" size="12" maxlength="16" require /></td>
      <td><input type="text" name="last_name" size="12" maxlength="18" required /></td>
      <td><input type="text" name="family_name" size="12" maxlength="15" /></td>
      <td><input type="text" name="short" size="2" maxlength="2" /></td>
      <td><?php  print_r($classroomSF);  ?></td>
      <td><?php  print_r($firstYearSF);  ?></td>
      <td><?php  print_r($lastYearSF);  ?></td>
      <td><input type="number" name="order" min="1" max="20" size="3" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="4" style="width: 175px;">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>