<tr id="createRow" class="c">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.01.2023 ********************** -->
   <form action="{{ route('przedmiot.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="text" name="name" size="30" maxlength="60" required /></td>
      <td><input type="text" name="short_name" size="10" maxlength="15" /></td>
      <td><input type="checkbox" name="actual" /></td>
      <td><input type="number" name="order_in_the_sheet" min="1" max="99" size="3" /></td>
      <td><input type="checkbox" name="expanded" /></td>
<!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2" style="min-width: 175px;">
         <button id="add"        class="btn btn-primary">dodaj</button>
         <button id="cancelAdd"  class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>