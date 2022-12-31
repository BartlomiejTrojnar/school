<tr id="createRow">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ********************** -->
   <form action="{{ route('sala.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="text" name="name" size="15" maxlength="20" required autofocus /></td>
      <td><input type="number" name="capacity" min="0" max="100" size="3" /></td>
      <td><input type="number" name="floor" min="0" max="2" size="2" /></td>
      <td><input type="number" name="line" min="1" max="10" size="2" /></td>
      <td><input type="number" name="column" min="1" max="10" size="2" /></td>
      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2" style="min-width: 175px;">
         <button id="add"       class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>