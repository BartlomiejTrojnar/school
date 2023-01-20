<tr id="createRow">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
   <form action="{{ route('szkola.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="text" name="name" size="40" autofocus required /></td>
      <td><input type="text" name="id_OKE" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2" style="width: 175px;">
         <button id="add"       class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>