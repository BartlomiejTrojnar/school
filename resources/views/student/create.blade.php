<tr id="createRow" class="c">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 15.01.2023 ********************** -->
   <form action="{{ route('uczen.store') }}" method="post" role="form">
   {{ csrf_field() }}
      <td colspan="2"><input type="text" name="first_name" size="10" maxlength="12" required autofocus /></td>
      <td><input type="text" name="second_name" size="10" maxlength="12" /></td>
      <td><input type="text" name="last_name" size="12" maxlength="15" required /></td>
      <td><input type="text" name="family_name" size="10" maxlength="12" /></td>
      <td><?php  print_r($sexSF);  ?></td>
      <td><input type="text" name="PESEL" size="11" maxlength="11" /></td>
      <td><input type="text" name="place_of_birth" size="10" maxlength="12" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td colspan="3" style="width: 175px;">
         <button id="add"       class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>