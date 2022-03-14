<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->
   <form action="{{ route('zadanie.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="text" name="name" size="60" maxlength="150" required /></td>
      <td><input type="number" name="points" min="1" max="1000" size="4" required /></td>
      <td><input type="text" name="importance" size="3" maxlength="4" required /></td>
      <td><input type="text" name="sheet_name" size="12" maxlength="20" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="5">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>